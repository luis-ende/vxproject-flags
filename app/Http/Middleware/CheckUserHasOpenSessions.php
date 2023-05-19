<?php

namespace App\Http\Middleware;

use App\Events\UserAuthenticatedEvent;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckUserHasOpenSessions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() &&
            $request->user()->getIsSubscriberAttribute()) {
            $sessions = DB::table('sessions')
                                ->where([
                                    ['user_id', $request->user()->id],
                                    ['id', '<>', $request->session()->getId()],
                                ])->count();
            if ($sessions > 0) {
                DB::table('sessions')
                    ->where([
                        ['user_id', $request->user()->id],
                        ['id', '<>', $request->session()->getId()],
                    ])
                    ->update([
                        'id' => DB::raw("concat('OUTMAN_', user_id,'_', id)"),
                        'user_id' => null,
                    ]);

                broadcast(new UserAuthenticatedEvent($request->user()->id));
            }
        }

        return $next($request);
    }
}
