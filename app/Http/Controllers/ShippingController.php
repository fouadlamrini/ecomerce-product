<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ShippingController extends Controller
{
    /** @var array<int, string> */
    private const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    public function index(): View
    {
        $shippings = Shipping::query()
            ->with(['order:id,order_number,user_id'])
            ->latest()
            ->paginate(10);

        return view('admin.shippings.index', [
            'shippings' => $shippings,
        ]);
    }

    public function create(): View
    {
        return view('admin.shippings.create', [
            'orders' => $this->orderOptions(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePayload($request);

        Shipping::query()->create($validated);

        return redirect()
            ->route('admin.shippings.index')
            ->with('success', 'Shipping created successfully.');
    }

    public function show(Shipping $shipping): View
    {
        $shipping->load('order:id,order_number');

        return view('admin.shippings.show', [
            'shipping' => $shipping,
        ]);
    }

    public function edit(Shipping $shipping): View
    {
        return view('admin.shippings.edit', [
            'shipping' => $shipping,
            'orders' => $this->orderOptions(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Shipping $shipping): RedirectResponse
    {
        $validated = $this->validatePayload($request, $shipping->id);
        $shipping->update($validated);

        return redirect()
            ->route('admin.shippings.index')
            ->with('success', 'Shipping updated successfully.');
    }

    public function destroy(Shipping $shipping): RedirectResponse
    {
        $shipping->delete();

        return redirect()
            ->route('admin.shippings.index')
            ->with('success', 'Shipping deleted successfully.');
    }

    /** @return array<int, array{id: string, order_number: string}> */
    private function orderOptions(): array
    {
        return Order::query()
            ->latest()
            ->get(['id', 'order_number'])
            ->map(fn (Order $order): array => [
                'id' => $order->id,
                'order_number' => $order->order_number,
            ])
            ->all();
    }

    /** @return array<string, mixed> */
    private function validatePayload(Request $request, ?string $ignoreShippingId = null): array
    {
        return $request->validate([
            'order_id' => ['required', 'uuid', 'exists:orders,id'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('shippings', 'tracking_number')->ignore($ignoreShippingId),
            ],
            'status' => ['required', Rule::in(self::STATUSES)],
            'shipped_at' => ['nullable', 'date'],
            'delivered_at' => ['nullable', 'date', 'after_or_equal:shipped_at'],
        ]);
    }
}
