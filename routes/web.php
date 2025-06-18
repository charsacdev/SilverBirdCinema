<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;

#========================CINEMA ROUTES==================#
Route::get('/', function () {
    return view('index');
})->name('login');


Route::get('/silverbird_pro_admin', function () {
    return view('adminlogin');
})->name('adminlogin');

Route::get('/verification', function () {
    return view('verification');
})->name('verification');


#============Dashboard Routes===========#
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard'); // Added a name for easier redirection/linking

    Route::get('/history', function () {
        return view('dashboard.history');
    })->name('history');

    #=========SUB ADMIN=======#
     Route::middleware(['role:sub_admin,super'])->group(function () { // <-- Corrected roles for these routes
        Route::get('/generate-ticket', function () {
            return view('dashboard.generate-ticket');
        })->name('generate-ticket');

        Route::get('/view-batch', function () {
            return view('dashboard.view-batch');
        })->name('view-batch');
    });

   
   #======ONLY SUPER ADMIN=====#
   Route::middleware(['role:super'])->group(function () { 

        Route::get('/partners', function () {
            return view('dashboard.partners');
        })->name('partners');

        Route::get('/settings', function () {
            return view('dashboard.settings');
        })->name('settings');
    });


    Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});