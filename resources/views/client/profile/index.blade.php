@extends('client.layouts.app')

@section('title', 'My Profile')

@section('content')
    <style>
        .profile-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; }
        .profile-title { margin: 0; font-size: 28px; font-weight: 800; }
        .profile-sub { margin: 0; color: #6b7280; font-size: 14px; }
        .section-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; margin-bottom: 18px; }
        .section-card h2 { margin: 0 0 14px; font-size: 17px; font-weight: 800; }
        .address-list { display: grid; gap: 14px; }
        .address-item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; background: #fcfcff; }
        .address-item.default { border-color: #f16743; box-shadow: 0 0 0 1px rgba(241, 103, 67, 0.2); }
        .address-meta { margin: 0 0 10px; font-size: 13px; color: #6b7280; }
        .badge { display: inline-block; margin-left: 8px; background: #fff1ed; color: #c2410c; border: 1px solid #fed7aa; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 700; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .field { margin-bottom: 10px; }
        .field label { display: block; margin-bottom: 4px; font-size: 12px; font-weight: 700; color: #6b7280; }
        .field input { width: 100%; box-sizing: border-box; border: 1px solid #e5e7eb; background: #fff; border-radius: 8px; padding: 10px 12px; font-size: 14px; }
        .checkbox-wrap { display: flex; align-items: center; gap: 8px; margin: 6px 0 12px; }
        .btn { border: 0; border-radius: 8px; padding: 10px 14px; font-weight: 700; cursor: pointer; font-size: 14px; }
        .btn-primary { background: #f16743; color: #fff; }
        .btn-secondary { background: #111827; color: #fff; }
        @media (max-width: 760px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>

    <div class="profile-head">
        <div>
            <h1 class="profile-title">My Profile</h1>
            <p class="profile-sub">Save your shipping addresses once, then reuse them at checkout.</p>
        </div>
    </div>

    <section class="section-card">
        <h2>Saved addresses</h2>
        @if ($addresses->isEmpty())
            <p class="profile-sub">No saved addresses yet.</p>
        @else
            <div class="address-list">
                @foreach ($addresses as $address)
                    <div class="address-item {{ $address->is_default ? 'default' : '' }}">
                        <p class="address-meta">
                            <strong>{{ $address->label ?: 'Address' }}</strong>
                            @if ($address->is_default)
                                <span class="badge">Default</span>
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

                            <div class="grid-2">
                                <div class="field">
                                    <label>Label</label>
                                    <input type="text" name="label" value="{{ old('label', $address->label) }}" placeholder="Home, Office...">
                                </div>
                                <div class="field">
                                    <label>Full name</label>
                                    <input type="text" name="full_name" value="{{ old('full_name', $address->full_name) }}" required>
                                </div>
                                <div class="field">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $address->phone) }}" required>
                                </div>
                                <div class="field">
                                    <label>Address line 1</label>
                                    <input type="text" name="line1" value="{{ old('line1', $address->line1) }}" required>
                                </div>
                                <div class="field">
                                    <label>Address line 2</label>
                                    <input type="text" name="line2" value="{{ old('line2', $address->line2) }}">
                                </div>
                                <div class="field">
                                    <label>City</label>
                                    <input type="text" name="city" value="{{ old('city', $address->city) }}" required>
                                </div>
                                <div class="field">
                                    <label>State</label>
                                    <input type="text" name="state" value="{{ old('state', $address->state) }}">
                                </div>
                                <div class="field">
                                    <label>Postal code</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" required>
                                </div>
                                <div class="field">
                                    <label>Country</label>
                                    <input type="text" name="country" value="{{ old('country', $address->country) }}" required>
                                </div>
                            </div>

                            <div class="checkbox-wrap">
                                <input id="default-{{ $address->id }}" type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }}>
                                <label for="default-{{ $address->id }}">Set as default address</label>
                            </div>

                            <button type="submit" class="btn btn-secondary">Save changes</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="section-card">
        <h2>Add new address</h2>
        <form method="POST" action="{{ route('client.addresses.store') }}">
            @csrf
            <div class="grid-2">
                <div class="field">
                    <label>Label</label>
                    <input type="text" name="label" value="{{ old('label') }}" placeholder="Home, Office...">
                </div>
                <div class="field">
                    <label>Full name</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div class="field">
                    <label>Address line 1</label>
                    <input type="text" name="line1" value="{{ old('line1') }}" required>
                </div>
                <div class="field">
                    <label>Address line 2</label>
                    <input type="text" name="line2" value="{{ old('line2') }}">
                </div>
                <div class="field">
                    <label>City</label>
                    <input type="text" name="city" value="{{ old('city') }}" required>
                </div>
                <div class="field">
                    <label>State</label>
                    <input type="text" name="state" value="{{ old('state') }}">
                </div>
                <div class="field">
                    <label>Postal code</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" required>
                </div>
                <div class="field">
                    <label>Country</label>
                    <input type="text" name="country" value="{{ old('country', 'MA') }}" required>
                </div>
            </div>

            <div class="checkbox-wrap">
                <input id="new-default" type="checkbox" name="is_default" value="1">
                <label for="new-default">Set as default address</label>
            </div>

            <button type="submit" class="btn btn-primary">Add address</button>
        </form>
    </section>
@endsection
