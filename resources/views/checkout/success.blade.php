@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-20 text-center">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full">
        <div class="w-24 h-24 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 1314 1 21"></path>
            </svg>
        </div>

        <h2 class="text-3xl font-black mb-4">Terima Kasih!</h2>

        <p class="text-slate-500 mb-8 leading-relaxed">
            Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil.
        </p>

        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-8">
            <div class="w-20 h-20 bg-w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-black mb-2">Selesaikan Pembayaran</h2>
            <p class="text-slate-600 mb-8">Mohon selesaikan pembayaran tiket Anda untuk event <strong>{{ $transaction->event->title }}</strong>.</p>

            <p class="text-xs text-slate-400">Order ID: {{ $transaction->order_id }}</p>

            <p class="text-xs text-slate-400 mt-3">Total Tagihan</p>
            <h3 class="text-4xl font-extrabold text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h3>

            <p class="text-slate-500 mb-8 leading-relaxed mt-6">
                Pembayaran untuk pesanan <strong>{{ $transaction->order_id }}</strong> sedang diproses atau telah berhasil.
            </p>

            <p class="text-xs text-slate-400">
                E-Ticket akan dikirim ke email Anda (<strong>{{ $transaction->customer_email }}</strong>) setelah pembayaran terkonfirmasi lunas.
            </p>

            <a href="{{ route('welcome') }}" class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                Kembali ke Beranda
            </a>

        </div>

    </main>

@endsection
