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
            "qris" => [
                "acquirer" => "gopay"
            ]
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
