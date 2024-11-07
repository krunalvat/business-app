<?php


// CheckAuthToken.php Middleware
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for Bearer Token
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
