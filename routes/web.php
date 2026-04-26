<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/info', [ProfileController::class, 'updateInfo'])->name('profile.updateInfo');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.storeAddress');
    Route::delete('/profile/addresses/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.destroyAddress');
    Route::post('/profile/payment-methods', [ProfileController::class, 'storePaymentMethod'])->name('profile.storePaymentMethod');
    Route::delete('/profile/payment-methods/{id}', [ProfileController::class, 'destroyPaymentMethod'])->name('profile.destroyPaymentMethod');
});

Route::get('/', function () {
    $categories = Category::all();
    $products = Product::latest()->take(6)->get();
    return view('welcome', compact('categories', 'products'));
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Productos públicos
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::get('/categories', [CategoriesController::class, 'index'])->name('categories.index');
Route::get('/products/{id}', [ProductsController::class, 'show'])
    ->whereNumber('id')
    ->name('products.show');

// Solo admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/products/create', [ProductsController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductsController::class, 'edit'])
        ->whereNumber('id')
        ->name('products.edit');
    Route::put('/products/{id}', [ProductsController::class, 'update'])
        ->whereNumber('id')
        ->name('products.update');
    Route::delete('/products/{id}', [ProductsController::class, 'destroy'])
        ->whereNumber('id')
        ->name('products.destroy');

    Route::get('/categories/create', [CategoriesController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoriesController::class, 'edit'])
        ->whereNumber('id')
        ->name('categories.edit');
    Route::put('/categories/{id}', [CategoriesController::class, 'update'])
        ->whereNumber('id')
        ->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])
        ->whereNumber('id')
        ->name('categories.destroy');
});