<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::withoutMiddleware([\App\Http\Middleware\SetCurrentAgency::class])->get('/debug', function (Request $request) {
    return "Host: " . $request->getHost();
});

Route::get('/', function () {
    $agency = app('currentAgency');

    return view('welcome', [
        'currentAgency' => $agency,
        'title' => $agency->name,
    ]);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return "Admin Dashboard for " . app('currentAgency')->name;
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('agencies', \App\Http\Controllers\AgencyController::class);
});
