<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class LoginUserExists
{
    public function handle($request, $next)
    {
        $email = $request->input('email');
        if ($email) {
            if (!User::where('email', $email)->exists()) {
                throw ValidationException::withMessages([
                    Fortify::username() => [trans('auth.username_failed')],
                ]);
            }
        }

        return $next($request);
    }
}