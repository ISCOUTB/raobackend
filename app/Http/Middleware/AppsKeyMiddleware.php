<?php

namespace App\Http\Middleware;

use Closure;
use Request;
use App\Apps;

class AppsKeyMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $hash = Request::input('appkey');
        $name = Request::input('appname');
        if ($hash == null || $name == null) {
            return response('AppKey y/o AppName faltantes', 401);
        }
        $app = Apps::where('hash', '=', $hash)->first();
        if (!$app) {
            return response('Llave de aplicación no valida.', 401);
        }
        if ($app->name != $name) {
            return response('Nombre de la aplicación no coincide con la llave.', 401);
        }

        return $next($request);
    }

}
