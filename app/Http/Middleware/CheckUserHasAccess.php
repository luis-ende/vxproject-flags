<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class CheckUserHasAccess
{
    /**
     * Handle an incoming request.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle(Request $request, Closure $next, ?string $redirectToRoute = null)
    {
        if (!$request->user() ||
            (!$request->user()->active)) {
            return $this->handleInvalidAccount($request);
        } else if ($request->user() && $request->user()->active && $request->user()->subscriber) {
            $expirationDate =
                Carbon::createFromFormat('Y-m-d', $request->user()->subscriber->expiration_date);
            if (!Carbon::today()->lte($expirationDate)) {
                return $this->handleInvalidAccount($request);
            }
        }

        return $next($request);
    }

    private function handleInvalidAccount(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->expectsJson()
            ? abort(403, 'Cuenta desactivada o suscripción ha expirado.')
            : \redirect()->back()->with('status', 'user-expired-deactivated');
    }
}
