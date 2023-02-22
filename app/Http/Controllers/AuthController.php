<?php

namespace App\Http\Controllers;

use Closure;
use Http;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function handle(Request $request, Closure $closure){
        $token = $request->header("Authorization");
        $tokenParsed = explode(" ", $token);
        $rq = Http::withBody($tokenParsed[1], 'text/plain')->post('http://spring-qs:8080/token/validar');
        return $rq->status() == 200 ? $closure($request) : response()->json(["message" => "No estas autorizado."], 401);
    }
}
