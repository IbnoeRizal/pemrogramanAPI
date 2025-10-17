<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\form;

class simulateSubmit extends Controller
{
    public function sendForm(Request $req){
        $htmlHelper = new \App\Services\Htmlhelper();

        // Ambil URL target (endpoint asli simulator Midtrans)
        $url = $req->input('_orig_action');
        if (!$url) {
            return response("URL asal tidak ditemukan.", 400);
        }

        // Hapus field lokal yang tidak perlu dikirim ke Midtrans
        $payload = $req->except(['_token', '_orig_action']);

        // Kirim ke Midtrans pakai POST
        $res = Http::asForm()->post($url, $payload);

        // balikin JSON status gagal, kalau gagal
        if($res->failed()) return response(["status" => "can't send the url"],$res->status());

        //buat Document object model
        $dom = $htmlHelper->createDom($res);
        if(!$dom) return response(["status" => "not a HTML document"],500);

        //copy semua elemen form
        $forms = $dom->getElementsByTagName("form");
        if(!$forms) return response(["status" => "no form element"],500);

        $form = $forms->item(0);//pilih indeks 0

        //url endpoint absolut untuk form
        $absoluteUrl_Endpoint = $htmlHelper->toAbsoluteUrl(
            $form->getAttribute('action')?: config("midtransAPI.simulator"),
            config("midtransAPI.simulator")
        );

        //ganti endpoint ke absolut endpoint
        $form->setAttribute('action',$absoluteUrl_Endpoint);
        $htmlHelper->cleanForm($form);

        //jadikan DOM ke array asosiatif, kirim dengan method post
        $res = Http::asForm()->post($absoluteUrl_Endpoint,$htmlHelper->formToArray($form));

        //gagal kirim ?
        if($res->failed()) return response(["status" => "can't post to payment simulator"],$res->status());

        //berhasil kirim, tunggu lewat webhook
        return response(["status" => "checking payment status"],200);

    }
}
