@extends('layouts.admin')

@section('title', 'Edit Kategori - Admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black mb-2">Edit Kategori</h1>
        <p class="text-slate-500 font-medium">Perbarui data kategori di bawah ini.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 max-w-2xl">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                    Nama Kategori <span class="text-red-600">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-3 rounded-xl border focus:ring-2 focus:ring-indigo-500 outline-none transition {{ $errors->has('name') ? 'border-red-500' : 'border-slate-200' }}"
                    placeholder="Contoh: Musik, Olahraga, Teknologi" value="{{ old('name', $category->name) }}">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-bold text-slate-700 mb-2">
                    Slug (Otomatis)
                </label>
                <input type="text" id="slug" readonly
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500"
                    value="{{ $category->slug }}">
                <p class="text-xs text-slate-400 mt-1">Slug dihasilkan secara otomatis dari nama kategori</p>
            </div>

            <div class="pt-6 flex gap-4">
                <a href="{{ route('admin.categories.index') }}"
                    class="px-6 py-3 border-2 border-slate-200 rounded-xl font-bold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
