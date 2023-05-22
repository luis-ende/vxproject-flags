<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Laravel\Jetstream\Actions\UpdateTeamMemberRole;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamMemberController as InertiaTeamMemberController;
use Laravel\Jetstream\Jetstream;

class TeamMemberController extends InertiaTeamMemberController
{
    /**
     * Add a new team member to a team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (Features::sendsTeamInvitations()) {
            app(InvitesTeamMembers::class)->invite(
                $request->user(),
                $team,
                $request->email ?: '',
                $request->role,
                $request->subscriptionType,
            );
        } else {
            app(AddsTeamMembers::class)->add(
                $request->user(),
                $team,
                $request->email ?: '',
                $request->role
            );
        }

        return back(303);
    }

    /**
     * Update the given team member's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $teamId, $userId)
    {
        $user = $request->user();
        if ($request->role) {
            app(UpdateTeamMemberRole::class)->update(
                $user,
                Jetstream::newTeamModel()->findOrFail($teamId),
                $userId,
                $request->role
            );
        }

        if ($request->subscriptionType) {
            Subscriber::where('user_id', $userId)->update([
                'subscription_type_id' => $request->subscriptionType,
                'expiration_date' =>
                    Subscriber::calculateExpirationDate($request->subscriptionType, today()),
            ]);
        }

        return back(303);
    }

    /**
     * Remove the given user from the given team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $teamId, $userId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(RemovesTeamMembers::class)->remove(
            $request->user(),
            $team,
            $user = Jetstream::findUserByIdOrFail($userId)
        );

        $user->delete();

        return back(303);
    }

    public function updateStatus(Request $request, $teamId, $userId)
    {
        $user = Jetstream::findUserByIdOrFail($userId);
        $user->active = !($user->active === true);
        $user->save();

        if ($request->user()->id === $user->id) {
            return redirect(config('fortify.home'));
        }

        return back(303);
    }
}
