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
Route::get('/gallery', [\App\Http\Controllers\Controller::class, 'gallery'])->name('gallery');
Route::get('/contact-us', \App\Livewire\ContactUs::class)->name('contact-us');
Route::view('/terms', 'terms')->name('terms');
Route::get('/package/{package}/details', \App\Livewire\Detail::class)->name('package.details');
Route::get('/package/{package}/reserve', [\App\Http\Controllers\Controller::class, 'reserve'])->name('package.reserve');
Route::POST('/reservation/{package}/store', [\App\Http\Controllers\Controller::class, 'storeReserve'])->name('reserve.store');
Route::get('/reservation/{reservation:uuid}/pay', [\App\Http\Controllers\PaymentController::class, 'pay'])->name('reservation.pay');
Route::get('/reservation/{reservation:uuid}', [\App\Http\Controllers\Controller::class, 'reserveDetail'])->name('reservation.detail');
Route::get('/test', function () {
    //dispatch(new \App\Jobs\sendRegisterEmailJob(1));
});
