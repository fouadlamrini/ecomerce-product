@extends('client.layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <h1 class="m-0 text-3xl font-extrabold">My Profile</h1>
            <p class="m-0 text-sm text-slate-500">Save your shipping addresses once, then reuse them at checkout.</p>
        </div>
        <a href="{{ route('client.orders.index') }}"
           class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50">
            My Orders
        </a>
    </div>

    <section class="mb-4 rounded-xl border border-slate-200 bg-white p-4.5">
        <h2 class="mb-3.5 text-lg font-extrabold">Saved addresses</h2>
        @if ($addresses->isEmpty())
            <p class="m-0 text-sm text-slate-500">No saved addresses yet.</p>
        @else
            <div class="grid gap-3.5">
                @foreach ($addresses as $address)
                    <div class="rounded-xl border bg-slate-50/70 p-3.5 {{ $address->is_default ? 'border-[#f16743] ring-1 ring-[#f16743]/20' : 'border-slate-200' }}">
                        <p class="mb-2.5 text-[13px] text-slate-500">
                            <strong>{{ $address->label ?: 'Address' }}</strong>
                            @if ($address->is_default)
                                <span class="ml-2 inline-block rounded-full border border-orange-200 bg-orange-50 px-2 py-0.5 text-[11px] font-bold text-orange-700">Default</span>
                            @endif
                            <br>
                            {{ $address->full_name }} - {{ $address->phone }}<br>
                            {{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }},
                            {{ $address->city }}{{ $address->state ? ', '.$address->state : '' }},
                            {{ $address->postal_code }}, {{ $address->country }}
                        </p>

                        <form method="POST" action="{{ route('client.addresses.update', $address) }}">
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Label</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="label" value="{{ old('label', $address->label) }}" placeholder="Home, Office...">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Full name</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="full_name" value="{{ old('full_name', $address->full_name) }}" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Phone</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="phone" value="{{ old('phone', $address->phone) }}" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Address line 1</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="line1" value="{{ old('line1', $address->line1) }}" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Address line 2</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="line2" value="{{ old('line2', $address->line2) }}">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">City</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="city" value="{{ old('city', $address->city) }}" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">State</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="state" value="{{ old('state', $address->state) }}">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Postal code</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-bold text-slate-500">Country</label>
                                    <input class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm" type="text" name="country" value="{{ old('country', $address->country) }}" required>
                                </div>
                            </div>

                            <div class="mb-3 mt-2 flex items-center gap-2">
                                <input id="default-{{ $address->id }}" type="checkbox" name="is_default" value="1" class="h-4 w-4 rounded border-slate-300 text-[#f16743] focus:ring-[#f16743]/30" {{ $address->is_default ? 'checked' : '' }}>
                                <label class="text-sm text-slate-700" for="default-{{ $address->id }}">Set as default address</label>
                            </div>

                            <button type="submit" class="rounded-lg bg-slate-900 px-3.5 py-2.5 text-sm font-bold text-white">Save changes</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-4.5">
        <h2 class="mb-3.5 text-lg font-extrabold">Add new address</h2>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="POST" action="{{ route('client.addresses.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1.5 flex items-center gap-2 text-xs font-semibold text-slate-600">Address name <span class="text-[11px] font-medium text-slate-400">(optional)</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70">
                                <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10m-10 5h16" /></svg>
                            </span>
                            <input class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="label" value="{{ old('label') }}" placeholder="Home, Office...">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Full name</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70">
                                <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><circle cx="12" cy="8" r="4" /><path stroke-linecap="round" stroke-linejoin="round" d="M5 20c1.6-3.3 4-5 7-5s5.4 1.7 7 5" /></svg>
                            </span>
                            <input class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Youssef El Amrani" required>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Phone</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70">
                                <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h4l2 5-2 2a16 16 0 0 0 4 4l2-2 5 2v4a2 2 0 0 1-2 2h-1C10.8 21 3 13.2 3 6V5a2 2 0 0 1 2-1z" /></svg>
                            </span>
                            <input class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. +212 6XX XXX XXX" required>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Street address</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70">
                                <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11z" /><circle cx="12" cy="10" r="2.6" /></svg>
                            </span>
                            <input class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="line1" value="{{ old('line1') }}" placeholder="Street, building number" required>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1.5 flex items-center gap-2 text-xs font-semibold text-slate-600">Apartment, suite, floor <span class="text-[11px] font-medium text-slate-400">(optional)</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70">
                                <svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M6 20V7l6-3 6 3v13M9 10h.01M9 13h.01M9 16h.01M15 10h.01M15 13h.01M15 16h.01" /></svg>
                            </span>
                            <input class="w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="line2" value="{{ old('line2') }}" placeholder="Apartment, suite, floor">
                        </div>
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">City</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70"><svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V8l7-4 7 4v13M9 12h.01M9 16h.01M15 12h.01M15 16h.01" /></svg></span>
                                <input class="h-[46px] w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="city" value="{{ old('city') }}" placeholder="Casablanca, Tangier..." required>
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 flex items-center gap-2 text-xs font-semibold text-slate-600">State <span class="text-[11px] font-medium text-slate-400">(optional)</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70"><svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" /></svg></span>
                                <input class="h-[46px] w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="state" value="{{ old('state') }}" placeholder="Region / State">
                            </div>
                        </div>
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600">Postal code</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70"><svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16v10H4z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8" /></svg></span>
                                <input class="h-[46px] w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" type="text" name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric" placeholder="e.g. 20000" required>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate-600">Country</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400/70"><svg class="h-[18px] w-[18px]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18" /></svg></span>
                            <select class="h-[46px] w-full appearance-none rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-14 pr-3 text-sm text-slate-900 focus:border-[#f16743] focus:outline-none focus:ring-2 focus:ring-[#f16743]/25" name="country" required>
                                @php $country = old('country', 'MA'); @endphp
                                <option value="MA" {{ $country === 'MA' ? 'selected' : '' }}>Morocco (MA)</option>
                                <option value="FR" {{ $country === 'FR' ? 'selected' : '' }}>France (FR)</option>
                                <option value="ES" {{ $country === 'ES' ? 'selected' : '' }}>Spain (ES)</option>
                                <option value="US" {{ $country === 'US' ? 'selected' : '' }}>United States (US)</option>
                                <option value="GB" {{ $country === 'GB' ? 'selected' : '' }}>United Kingdom (GB)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <label class="mt-3 inline-flex cursor-pointer items-center gap-2 text-sm text-slate-700" for="new-default">
                    <input id="new-default" type="checkbox" name="is_default" value="1" class="h-4 w-4 rounded border-slate-300 text-[#f16743] focus:ring-[#f16743]/30">
                    <span>Set as default address</span>
                </label>

                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-[1fr_130px]">
                    <button type="submit" class="inline-flex h-[46px] w-full items-center justify-center rounded-xl bg-linear-to-b from-[#ff996f] via-[#ff7f50] to-[#f16743] px-4 text-sm font-extrabold text-white">Save Address</button>
                    <button type="reset" class="inline-flex h-[46px] w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-700">Cancel</button>
                </div>
            </form>
        </div>
    </section>
@endsection
