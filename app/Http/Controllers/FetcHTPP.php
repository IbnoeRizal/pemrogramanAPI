<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class FetcHTPP extends Controller
{
    public function formSimulator(){
        $htmlHelper = new \App\Services\Htmlhelper();
        $url = config("midtransAPI.simulator");
        $res = Http::get($url);

        if($res->failed()) return response("Gagal fetch HTML ".$res->status(),500);

        $dom = $htmlHelper->createDom($res);

        //buat form
        $forms = $dom->getElementsByTagName('form');
        if($forms->length == 0) return response("Form tidak ditemukan",404);
        //ambil form pertama
        $form = $forms[0];

        //bentuk endpoint asli
        $origAction = $form->getAttribute('action') ?:$url;
        $absAction = $htmlHelper->toAbsoluteUrl($origAction,$url);

        //menambahkan data original endpoint ke atribut data-orig-action
        $form->setAttribute('data-orig-action', $absAction);

        // set action agar submit ke route
        $form->setAttribute('action', route('midtrans.submit'));

        // --- sisipkan hidden input _orig_action dan CSRF token ---
        // buat input _orig_action
        $inputOrig = $dom->createElement('input');
        $inputOrig->setAttribute('type', 'hidden');
        $inputOrig->setAttribute('name', '_orig_action');
        $inputOrig->setAttribute('value', $absAction);
        $form->appendChild($inputOrig);

        // Jika mau ubah semua input yang diperlukan (mis: disabled -> remove), bisa diproses di sini.
        $htmlHelper->cleanForm($form);

        // sisipkan CSRF token (soalnya form nanti submit ke Laravel)
        $csrf = csrf_token();
        $inputCsrf = $dom->createElement('input');
        $inputCsrf->setAttribute('type', 'hidden');
        $inputCsrf->setAttribute('name', '_token');
        $inputCsrf->setAttribute('value', $csrf);
        $form->appendChild($inputCsrf);


        // ambil outerHTML dari node form
        $formHtml = $htmlHelper->getOuterHtml($form);

        return response($formHtml,200);

    }

}
