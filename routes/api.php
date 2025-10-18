<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhookendp;




Route::post('/submit/midtrans/notif',[Webhookendp::class,'editData']); //notifikasi endpoint;
