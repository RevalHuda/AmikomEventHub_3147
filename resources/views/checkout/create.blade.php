@extends('layouts.app')

@section('content')
<main class="max-w-4xl mx-auto px-6 py-12">
        <div class="mb-12">
            <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6 hover:gap-3 transition text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Detail Event
            </a>

            <h1 class="text-4xl md:text-5xl font-black mb-2">Checkout Tiket</h1>
            <p class="text-slate-600 font-medium">Lengkapi data Anda untuk melanjutkan ke pembayaran</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Card -->
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-6 text-slate-800">📋 Data Pemesan (Tanpa Login)</h3>

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="customer_name" placeholder="Contoh: Bambang Sugono"
                            class="w-full px-5 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition font-medium text-slate-900"
                            required value="{{ old('customer_name') }}">
                        @error('customer_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">Email</label>
                            <input type="email" name="customer_email" placeholder="nama@example.com"
                                class="w-full px-5 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition font-medium text-slate-900"
                                required value="{{ old('customer_email') }}">
                            <p class="text-slate-500 text-xs mt-2 font-medium">💡 E-tiket akan dikirim ke email ini</p>
                            @error('customer_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3 uppercase tracking-wider">No. WhatsApp</label>
                            <input type="tel" name="customer_phone" placeholder="08123456789"
                                class="w-full px-5 py-3 bg-white border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition font-medium text-slate-900"
                                required value="{{ old('customer_phone') }}">
                            @error('customer_phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg shadow-lg hover:bg-indigo-700 active:scale-95 transition-all duration-200">
                        💳 Lanjut ke Pembayaran
                    </button>

                    <p class="text-center text-xs text-slate-500 font-medium">Dengan melanjutkan, Anda menyetujui <a href="#" class="text-indigo-600 hover:underline">Syarat & Ketentuan</a> kami.</p>
                </form>
            </div>

            <!-- Summary Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm sticky top-32">
                    <h3 class="text-xl font-bold mb-6">Pesanan Anda</h3>

                    <!-- Event Info -->
                    <div class="mb-8 pb-8 border-b">
                        <img src="{{ ($event->poster_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/300x200' }}"
                            alt="Event" class="w-full rounded-2xl object-cover mb-4">
                        
                        <h4 class="font-extrabold text-lg text-slate-800 mb-2">{{ $event->title }}</h4>
                        <p class="text-sm text-slate-600 mb-1">📅 {{ \Carbon\Carbon::parse($event->date)->format('d M Y H:i') }}</p>
                        <p class="text-sm text-slate-600">📍 {{ $event->location }}</p>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 font-medium">Harga Tiket</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="text-slate-600 font-medium">Biaya Layanan</span>
                            <span class="font-bold text-slate-900">Rp 5.000</span>
                        </div>

                        <div class="flex justify-between items-center pt-3">
                            <span class="text-lg font-extrabold text-slate-900">Total Bayar</span>
                            <span class="text-2xl font-black text-indigo-600">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
