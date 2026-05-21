@extends('layouts.admin')

@section('title', 'Tambah Kategori - Admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black mb-2">Tambah Kategori Baru</h1>
        <p class="text-slate-500 font-medium">Buat kategori baru untuk mengorganisir event.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 max-w-2xl">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                    Nama Kategori <span class="text-red-600">*</span>
                </label>
                <input type="text" id="name" name="name"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Musik, Olahraga, Teknologi" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-1">Masukkan nama kategori yang unik</p>
            </div>

            <div class="pt-6 flex gap-4">
                <a href="{{ route('admin.categories.index') }}"
                    class="px-6 py-3 border-2 border-slate-200 rounded-xl font-bold text-slate-700 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700 transition">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
@endsection
