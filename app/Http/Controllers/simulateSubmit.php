<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class simulateSubmit extends Controller
{
    public function sendForm(Request $req){
        // Ambil URL target (endpoint asli simulator Midtrans)
        $url = $req->input('_orig_action');
        if (!$url) {
            return response("URL asal tidak ditemukan.", 400);
        }

        // Hapus field lokal yang tidak perlu dikirim ke Midtrans
        $payload = $req->except(['_token', '_orig_action']);

        // Kirim ke Midtrans pakai POST
        $res = Http::asForm()->post($url, $payload);


        // Balikkan hasil dari Midtrans ke browser (biar bisa dilihat)
        return response($res->body(), $res->status())
            ->header('Content-Type', $res->header('Content-Type'));
    }
}
