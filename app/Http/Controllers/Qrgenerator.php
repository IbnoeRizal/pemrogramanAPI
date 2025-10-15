<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\CoreApi;


class Qrgenerator extends Controller
{
    public function createQrispayment(Request $req){

        $req->validate([
            'name' => 'required|string|max:50',
            'NIM' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0'
        ]);


        Config::$serverKey = config("midtransAPI.server_key");
        Config::$clientKey = config("midtransAPI.client_key");
        Config::$isProduction = config("midtransAPI.is_production");
        Config::$isSanitized = config("midtransAPI.is_sanitized");
        Config::$is3ds = config("is3ds");

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => 'PAR-' . uniqid(),
                'gross_amount' => $req->harga,
            ],
            'customer_details' => [
                'name' => $req->name,
                'email' => $req->NIM . "student@unisba.ac",
            ],
        ];

        try{
            $response = CoreApi::charge($params);
            return response()->json($response);
        }
        catch(\Exception $err){
            return response()->json(['error' => $err->getMessage()],500);
        }

    }
}
