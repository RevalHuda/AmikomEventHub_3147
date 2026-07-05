<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        // Ambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Cegah Check-out Jika Tiket Habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . time() . '-' . Str::random(5);

        $totalPrice = $event->price + 5000; // Menambahkan biaya admin (dummy)

        // 4. Merekam Transaksi ke Database
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'Pending', // Status Awal
        ]);

        // 5. Susun Paket Array Data Transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
        ];

        try {
            // --- INTEGRASI SNAP MIDTRANS ---

            // Konfigurasi Kredensial Environment Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false; // Mode Sandbox!
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Susun Paket Array Data Transaksi
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                ],
            ];

            // Perintah Tembak Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update rekaman kami bawha transaksi terkait sudah memiliki id token pelususan
            $transaction->update(['snap_token' => $snapToken]);

            // Redirect ke halaman antarmuka pembayaran final pelanggan
            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            // Jika error (transaksi tidak ada di Midtrans, koneksi terputus), kembalikan ke beranda
            return back()->with('error', 'Gagal memproses pembayaran jaringan : ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        // Ambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = Transaction::where('order_id', $order_id)->firstOrFail();

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        // Ambil daftar kategori untuk keperluan menu footer
        $categories = \App\Models\Category::all();

        $transaction = Transaction::where('order_id', $order_id)->firstOrFail();

        // Validasi status pembayaran asli dari Midtrans (Mencegah manipulasi URL)
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        try {
            $midtransStatus = \Midtrans\Transaction::status($order_id);

            // Hanya ubah status menjadi sukses jika Midtrans mengonfirmasi pembayaran lunas
            if (in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
                $transaction->update(['status' => 'success']);
            }

        } catch (\Exception $e) {
            // Jika error (transaksi tidak ada di Midtrans, koneksi terputus), kembalikan ke beranda
            return redirect()->route('welcome')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }

    public function notification(Request $request)
    {
        // Webhook handler dari Midtrans untuk update status pembayaran
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        $notifBody = $request->getContent();
        $notifData = json_decode($notifBody, true);

        // Validasi dan update status transaksi berdasarkan notifikasi Midtrans
        try {
            $midtransStatus = \Midtrans\Transaction::status($notifData['order_id']);
            $transaction = Transaction::where('order_id', $notifData['order_id'])->first();

            if ($transaction) {
                // Update status berdasarkan transaction_status dari Midtrans
                if ($midtransStatus->transaction_status == 'capture' || $midtransStatus->transaction_status == 'settlement') {
                    $transaction->update(['status' => 'success']);
                } elseif ($midtransStatus->transaction_status == 'deny' || $midtransStatus->transaction_status == 'cancel' || $midtransStatus->transaction_status == 'expire') {
                    $transaction->update(['status' => 'failed']);
                }
            }

            // Return response 200 OK ke Midtrans
            return response()->json(['status' => 'ok'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
