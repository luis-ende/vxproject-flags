<?php

namespace App\Http\Middleware;

use App\Models\Team;
use App\Models\TeamType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $teamSubscribersId = null;
        if (auth()->user()) {
            $teamSubscribersId = Cache::rememberForever('team_subscribers_id', function() {
                return Team::firstWhere('name', TeamType::Suscriptores)->value('id');
            });
        }

        return array_merge(parent::share($request), [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'teamSubscribersId' => $teamSubscribersId,
        ]);
    }
}
