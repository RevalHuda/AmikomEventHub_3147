@extends('layouts.app')

@section('title', 'Pembayaran - ' . $transaction->event->title)

@section('content')
<main class="max-w-3xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl border border-slate-200 p-12 shadow-sm inline-block w-full">
        <div class="w-20 h-20 bg-w-20 h-20 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2.25"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-black mb-2">Selesaikan Pembayaran</h2>
        <p class="text-slate-500 mb-8">Mohon selesaikan pembayaran tiket Anda untuk event <strong>{{ $transaction->event->title }}</strong>.</p>

        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 mb-8">
            <p class="text-sm text-slate-600">Order ID: {{ $transaction->order_id }}</p>

            <p class="text-xs text-slate-400 mt-1">Total Tagihan</p>
            <h3 class="text-4xl font-extrabold text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h3>

            <p class="text-xs text-slate-400 mt-4">Order ID: {{ $transaction->order_id }}</p>
        </div>

        <button id="pay-button" class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition animate-bounce-in">
            Bayar Sekarang
        </button>

        </div>

    </main>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from previous step
            snap.pay('{{ $transaction->snap_token }}', {
                // Optional
                onSuccess: function(result) {
                    window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
                },

                // Optional
                onPending: function(result) {
                    window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
                },

                // Optional
                onError: function(result) {
                    alert("Pembayaran Gagal!");
                }
            });
        };
    </script>

    <style>
        @keyframes bounce-in {
            0% { transform: scale(0.9); opacity: 0; }
            70% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }

        .animate-bounce-in { animation: bounce-in 0.4s ease-out forwards; }
    </style>

@endsection
