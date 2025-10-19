<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Ismahasiswa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->validate([
            'NIM' => 'required|string'
        ]);

        $checker = DB::table('passwordwifi')->where('nim',$request->NIM)->exists();
        if(!$checker) return redirect()->route('login')->with('error', 'Kamu bukan mahasiswa');
        return $next($request);
    }
}
