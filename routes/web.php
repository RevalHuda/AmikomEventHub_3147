<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\AuthController;

// Halaman Beranda (Home)
Route::get('/', [EventController::class, 'index'])->name('welcome');

// Halaman Detail Event
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Routes Checkout
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// Route untuk testing - update status transaksi (hapus di production!)
Route::get('/test/update-status/{order_id}', function($order_id) {
    $transaction = \App\Models\Transaction::where('order_id', $order_id)->first();
    if ($transaction) {
        $transaction->update(['status' => 'success']);
        return "Status transaksi $order_id berhasil diubah menjadi success!";
    }
    return "Transaksi tidak ditemukan";
});

// Halaman Ticket (Setelah Bayar)
Route::get('/ticket', function () {
    return view('ticket');
})->name('ticket');


// Redirect generic /login to admin login
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Grouping untuk URL berawalan /admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Rute Login bebas akses
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Route Administrasi di balik tembok (Middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', AdminEventController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });
});
