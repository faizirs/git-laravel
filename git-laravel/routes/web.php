<?php

use Illuminate\Support\Facades\Route;

// untuk memanggil conroller
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;

// menampilkan halaman login
Route::get('/', [LoginController::class, 'index'])->name('home');
// Route untuk halaman pendaftaran pengguna
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
//untuk halaman login dan proses autentikasi
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'check_login'])->name('login.check_login');
// grup route middleware 'auth', cuma bisa diakses pengguna yang sudah login
Route::middleware(['auth'])->group(function () {
    // menampilkan daftar produk
    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    // logout pengguna
    Route::get('/logout', [ProductController::class, 'logout'])->name('product.logout');
    // menampilkan form pembuatan produk baru
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    // menyimpan produk baru
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    // menghapus produk
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    // menampilkan detail produk
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    // menampilkan form edit produk
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    // update produk
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
});
