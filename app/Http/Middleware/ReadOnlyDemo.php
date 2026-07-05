<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlyDemo
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->username === 'demo') {
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                
                // Allow logout so they are not trapped
                if ($request->is('logout') || $request->is('portal/logout')) {
                    return $next($request);
                }
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Aksi ini dimatikan untuk Akun Demo.'], 403);
                }
                
                return back()->withErrors(['demo_restricted' => 'Ini akun demo, bro. Fitur tambah/edit/hapus data dimatikan.'])->withInput();
            }
        }

        return $next($request);
    }
}
