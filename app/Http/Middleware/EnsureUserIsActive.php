<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // dd($email);

        if ($user->is_active == 1) {

            return $next($request);
        } else {
            Auth::logout();

            // return redirect('login')->with('errors', 'cannot login, the user is deactivated.');
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is deactivated.',
            ]);
        }
    }
}
