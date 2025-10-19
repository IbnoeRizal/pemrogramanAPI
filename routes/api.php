<?php

use App\Http\Controllers\showpassword;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhookendp;




Route::post('/submit/midtrans/notif',[Webhookendp::class,'editData']); //notifikasi endpoint;
Route::post('/check/password',[showpassword::class,'getPassword'])->name('check.password');
