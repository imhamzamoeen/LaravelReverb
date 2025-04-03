<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/public-event', function () {
    $user = User::find(1);
    broadcast(new \App\Events\SendMessageEvent());
});

Route::get('/que-event', function () {
    broadcast(new \App\Events\SendMessageEvent());
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/private-event', function () {

        broadcast(new \App\Events\PrivateChannelEvent(User::find(1)));
    });

    Route::get('/presence-event', function () {

        broadcast(new \App\Events\SendMessageEvent(User::find(1)));
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
