@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-black mb-2">Laporan Transaksi</h1>
        <p class="text-slate-500 font-medium">Pantau arus kas dan penjualan tiket Anda.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate--100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Detail Pembeli</th>
                        <th class="px-8 py-4">Event</th>
                        <th class="px-8 py-4">Tgl Transaksi</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse ($transactions as $strx)
                        <tr class="hover:bg-slate-50/50 transition {{ $strx->status == 'pending' ? 'text-slate-400' : '' }}">
                            <td class="px-8 py-6">
                                <span class="font-mono font-bold px-3 py-1 bg-slate-100 text-sm rounded-lg text-sm">
                                    {{ $strx->order_id }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-slate-800">{{ $strx->customer_name }}</p>
                                <p class="text-xs text-slate-500">{{ $strx->customer_email }}<br>{{ $strx->customer_phone }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-medium text-slate-700">{{ $strx->event->title ?? '??' }}</p>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500">
                                {{ $strx->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-8 py-6">
                                @if ($strx->status === 'settlement' || $strx->status === 'success')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase ring-1 ring-green-200">Success</span>
                                @elseif($strx->status === 'pending')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase ring-1 ring-orange-200">Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200">{{ $strx->status }}</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-indigo-600 font-black">Rp {{ number_format($strx->total_price, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center text-slate-400">
                                <p class="text-lg font-medium">Belum ada transaksi</p>
                                <p class="text-sm mt-2">Transaksi akan muncul di sini setelah ada pembeli</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-8 py-6 bg-slate-50/50 border-t">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
