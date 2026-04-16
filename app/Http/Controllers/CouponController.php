<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(Request $request): View
    {
        $coupons = Coupon::query()
            ->latest()
            ->paginate(10);

        $editCoupon = null;
        $editId = (string) $request->query('edit', '');
        if ($editId !== '') {
            $editCoupon = Coupon::query()->whereKey($editId)->first();
        }

        return view('admin.promotions', [
            'coupons' => $coupons,
            'editCoupon' => $editCoupon,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        Coupon::query()->create($validated);

        return redirect()
            ->route('admin.promotions')
            ->with('success', 'Promotion created successfully.');
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $this->validatePayload($request, $coupon->id);
        $coupon->update($validated);

        return redirect()
            ->route('admin.promotions')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()
            ->route('admin.promotions')
            ->with('success', 'Promotion deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validatePayload(Request $request, ?string $ignoreCouponId = null): array
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($ignoreCouponId),
            ],
            'type' => ['required', Rule::in(['fixed', 'percentage'])],
            'value' => ['required', 'numeric', 'min:0.01'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['code'] = strtoupper(trim((string) $validated['code']));
        $validated['is_active'] = $request->boolean('is_active');

        if (($validated['type'] ?? '') === 'percentage' && (float) $validated['value'] > 100) {
            throw ValidationException::withMessages([
                'value' => 'Percentage value cannot be more than 100.',
            ]);
        }

        return $validated;
    }
}
