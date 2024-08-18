<?php

use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Jetstream;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => false,
        'status' => session()->get('status')
        /*'canRegister' => Route::has('register'),*/
    ]);
})->name('homepage');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'active',
])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'show'])->name('dashboard');
    Route::get('/config-panel', [\App\Http\Controllers\ConfigPanelController::class, 'show'])->name('config-panel.show');
    Route::put('/config-panel/{groupId}/tipos-suelo-colors', [\App\Http\Controllers\ConfigPanelController::class, 'tiposSueloColors'])->name('config-panel.tipos-suelo-colors');
    Route::get('/vx-flags/info/{flagId}', [\App\Http\Controllers\FlagsController::class, 'getVxFlagInfo'])->name('vx-flags.info');
    Route::delete('/vx-flags/{flagId}', [\App\Http\Controllers\FlagsController::class, 'destroy'])->name('vx-flags.destroy');
    Route::put('/vx-flags/group/{groupId}', [\App\Http\Controllers\FlagsController::class, 'groupUpdate'])->name('vx-flags.group.update');
    Route::get('/vx-flags/group/{groupId}/list', [\App\Http\Controllers\FlagsController::class, 'getFlagsByGroup'])
        ->middleware('cache.headers:private;max_age=3600;etag')
        ->name('vx-flags.group.list');
    Route::post('/vx-flags/tipos-suelo/import', [\App\Http\Controllers\FlagsController::class, 'flagsTiposSueloImport'])->name('vx-flags.tipos-suelo.import');
});

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terminos-de-uso', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/politicas-de-privacidad', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    $authMiddleware = config('jetstream.guard')
        ? 'auth:'.config('jetstream.guard')
        : 'auth';

    $authSessionMiddleware = config('jetstream.auth_session', false)
        ? config('jetstream.auth_session')
        : null;

    Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])
            ->name('profile.show');

        Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])
            ->name('other-browser-sessions.destroy');

        Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])
            ->name('current-user-photo.destroy');

        if (Jetstream::hasAccountDeletionFeatures()) {
            Route::delete('/user', [CurrentUserController::class, 'destroy'])
                ->name('current-user.destroy');
        }

        Route::group(['middleware' => 'verified'], function () {
            // API...
            if (Jetstream::hasApiFeatures()) {
                Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
                Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
                Route::put('/user/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
                Route::delete('/user/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
            }

            // Teams...
            if (Jetstream::hasTeamFeatures()) {
                Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
                Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
                Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
                Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
                Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
                Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');
                Route::post('/teams/{team}/members', [TeamMemberController::class, 'store'])->name('team-members.store');
                Route::put('/teams/{team}/members/{user}/update', [TeamMemberController::class, 'update'])->name('team-members.update');
                Route::delete('/teams/{team}/members/{user}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');
                Route::put('/teams/{team}/members/{user}/update-status', [TeamMemberController::class, 'updateStatus'])->name('team-members.update-status');

                Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
                    ->middleware(['signed'])
                    ->name('team-invitations.accept');

                Route::delete('/team-invitations/{invitation}', [TeamInvitationController::class, 'destroy'])
                    ->name('team-invitations.destroy');
            }
        });
    });
});
