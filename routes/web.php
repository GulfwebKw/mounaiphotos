<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\Controller::class, 'index'])->name('home');
Route::get('/contact-us', [\App\Http\Controllers\Controller::class, 'index'])->name('contact-us');
Route::get('/test', function () {
    //dispatch(new \App\Jobs\sendRegisterEmailJob(1));
});
