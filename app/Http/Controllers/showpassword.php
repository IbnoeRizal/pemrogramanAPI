<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class showpassword extends Controller
{
    public function getPassword(Request $req){
        $req->validate([
            'OrderId' => 'required|string',
            'NIM' => 'required|string|size:11'
        ]);

        $data = Cache::get($req->OrderId,'pending');
        if($data !== 'settlement') return response()->json(['redirect' => route('login', ['error' => 'Transaksi Belum Diproses'])]);

        $password = DB::select('select birth_date from passwordwifi where nim = ?', [$req->NIM]);
        $birthDate = isset($password[0]) ? $password[0]->birth_date : 'Hacker ya ?';

        return response()->json(['redirect' => route('get.password', ['password' => $birthDate]),]);

    }
}
