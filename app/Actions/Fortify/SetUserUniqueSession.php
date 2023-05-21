<?php

namespace App\Actions\Fortify;

use App\Events\UserAuthenticatedEvent;
use Illuminate\Support\Facades\Session;

class SetUserUniqueSession
{
    public function handle($request, $next)
    {
        if ($request->user() &&
            $request->user()->getIsSubscriberAttribute()) {
            $previous_session = $request->user()->session_id;
            if ($previous_session) {
                Session::getHandler()->destroy($previous_session);
            }

            $request->user()->session_id = Session::getId();
            $request->user()->save();

            if ($previous_session) {
                broadcast(new UserAuthenticatedEvent($request->user()->id));
            }
        }

        return $next($request);
    }
}