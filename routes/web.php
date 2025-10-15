<?php

use App\Http\Controllers\Qrgenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('LOG');
});

Route::post('/Generateqr',function(Request $req){
    $req->merge(['harga' => 2000]);

    $data = app(Qrgenerator::class)->createQrispayment($req)->getData(true);

    return view('displaytransaction',
    [
        'type' => (isset($data['status_code']) && (int)$data['status_code'] < 400)? $data['payment_type'] : 'err',
        'message' => $data['status_message']?? $data['error'],
        'mataUang' => $data['currency']?? null,
        'src' => $data['actions'][0]['url']?? null,
        'Rp' => $data['gross_amount']?? 0
    ]);

})->name('generate.qr');
