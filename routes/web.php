<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication routes manual
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Room routes (public)
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// User reservation routes
Route::middleware(['auth'])->group(function () {
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create/{room}', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations/{room}', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Rooms management
    Route::get('/rooms', [RoomController::class, 'adminIndex'])->name('admin.rooms.index');
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('admin.rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('admin.rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('admin.rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('admin.rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('admin.rooms.destroy');

    // Reservations management
    Route::get('/reservations', [ReservationController::class, 'adminIndex'])->name('admin.reservations.index');
    Route::post('/reservations/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('admin.reservations.confirm');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('admin.reservations.destroy');
    Route::delete('/admin/reservations/bulk-delete', [ReservationController::class, 'bulkDelete'])->name('admin.reservations.bulkDelete');
});