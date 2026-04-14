<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View
    {
        $addresses = $request->user()
            ->addresses()
            ->latest()
            ->get();

        return view('client.profile.index', [
            'addresses' => $addresses,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $payload = $this->validateAddress($request);

        $hasAddresses = $user->addresses()->exists();
        $makeDefault = $request->boolean('is_default') || ! $hasAddresses;

        if ($makeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            ...$payload,
            'is_default' => $makeDefault,
        ]);

        return $this->redirectAfterSave($request, 'Address added successfully.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        $user = $request->user();
        if ($address->user_id !== $user->id) {
            abort(403);
        }

        $payload = $this->validateAddress($request);
        $makeDefault = $request->boolean('is_default');

        if ($makeDefault) {
            $user->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update([
            ...$payload,
            'is_default' => $makeDefault,
        ]);

        if (! $makeDefault && ! $user->addresses()->where('is_default', true)->exists()) {
            $address->update(['is_default' => true]);
        }

        return $this->redirectAfterSave($request, 'Address updated successfully.');
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['nullable', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
        ]);
    }

    private function redirectAfterSave(Request $request, string $message): RedirectResponse
    {
        $destination = $request->input('redirect_to');
        if ($destination === 'checkout') {
            return redirect()->route('client.checkout')->with('success', $message);
        }

        return redirect()->route('client.profile')->with('success', $message);
    }
}
