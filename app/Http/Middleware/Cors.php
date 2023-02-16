<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Cors {


    public function handle(Request $request, Closure $next) {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With');
        $response->header('Access-Control-Content-Type', 'application/json');
        $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
        $response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
    }




}

