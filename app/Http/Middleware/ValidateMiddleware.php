<?php

namespace App\Http\Middleware;

use App\Models\Departement;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $departement = Departement::query()
            ->where('id', $request->departement_id)
            ->where('faculty_id', $request->faculty_id)
            ->exists();

        if (!$departement) {
            flashMessage('Program studi yang anda pilih tidak terdaftar pada fakultas yang anda pilih', 'error');
            return back();
        }
        return $next($request);
    }
}
