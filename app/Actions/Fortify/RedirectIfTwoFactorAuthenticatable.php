<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class RedirectIfTwoFactorAuthenticatable extends \Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable
{
    /**
     * Throw a failed authentication validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailedAuthenticationException($request)
    {
        // User's existence was already checked in a previous pipeline (LoginUserExists).

        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            'password' => [trans('auth.password_failed')],
        ]);
    }
}