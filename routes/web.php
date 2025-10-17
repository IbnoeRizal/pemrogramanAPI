<?php

use App\Http\Controllers\FetcHTPP;
use App\Http\Controllers\Qrgenerator;
use App\Http\Controllers\simulateSubmit;
use App\Http\Controllers\webhookendp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

Route::get('/', function () {
    return view('LOG');
});

Route::post('/Generateqr',function(Request $req){
    $req->merge(['harga' => 2000]);

    $data = app(Qrgenerator::class)->createQrispayment($req)->getData(true);

    if (isset($data['expiry_time'])) {
        $expiry = Carbon::parse($data['expiry_time']);
        $ttl = $expiry->diffInSeconds(Carbon::now());

        Cache::put($data['order_id'], $data['transaction_status'], $ttl);
    }

    return view('displaytransaction',
    [
        'type' => (isset($data['status_code']) && (int)$data['status_code'] < 400)? $data['payment_type'] : 'err',
        'message' => $data['status_message']?? $data['error'],
        'mataUang' => $data['currency']?? null,
        'src' => $data['actions'][0]['url']?? null,
        'Rp' => $data['gross_amount']?? 0,
        'OrderId' => $data['order_id']?? ""
    ]);
})->name('generate.qr');

Route::get('/formsimulation',[FetcHTPP::class,'formSimulator'])->name('form.simulation'); // dapatkan form dari simulator
Route::post('/submit/midtrans/simulation',[simulateSubmit::class,'sendForm'])->name('midtrans.submit'); //submit url qr ke simulator
Route::post('/submit/midtrans/notif',[webhookendp::class,'editData'])->name('midtrans.notif'); //notifikasi endpoint
Route::post('/submit/midtrans/pollstatus',[webhookendp::class,'getData'])->name('midtrans.statusNotif'); //polling untuk user
