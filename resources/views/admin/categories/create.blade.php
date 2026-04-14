@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')
    <div class="max-w-[760px]">

        @if ($errors->any())
            <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2.5 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3.5">
                <label class="mb-1.5 block text-sm font-bold" for="name">Name</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="name" type="text" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3.5">
                <label class="mb-1.5 block text-sm font-bold" for="bg_image">Background Image</label>
                <input class="w-full rounded-lg border border-slate-300 px-3 py-2.5" id="bg_image" type="file" name="bg_image" accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input class="h-4 w-4 rounded border-slate-300 text-[#f16743] focus:ring-[#f16743]/30" type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <a class="mr-3 text-sm text-slate-600" href="{{ route('admin.categories.index') }}">Back</a>
            <button class="rounded-lg bg-[#f16743] px-4 py-2 text-sm font-bold text-white" type="submit">Create</button>
        </form>
    </div>
@endsection
