<?php

namespace App\Http\Controllers;
use Midtrans\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class Webhookendp extends Controller
{
    public function editData(Request $request){
        $notif = new Notification();
        $signatureKey = hash('sha512',
            $notif->order_id .
            $notif->status_code .
            $notif->gross_amount .
            config('midtransAPI.server_key')
        );

        if($notif->signature_key !== $signatureKey) abort(403, 'Invalid signature');
        if(!Cache::has($notif->order_id)) return response()->json(['status' => 'ignored']);

        Cache::put($notif->order_id,$notif->transaction_status);
        return response()->json(['status' => 'ok']);
    }

    public function getData(Request $req){

        $req->validate([
            'key' => 'required|string|max:255'
        ]);

        $value = Cache::get($req->key, function () {
            return "canceled";
        });

        if($value == "settlement") Cache::delete($req->key);
        return response(["state" => $value],200);
    }
}
