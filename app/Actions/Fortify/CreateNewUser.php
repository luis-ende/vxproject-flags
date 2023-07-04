<?php

namespace App\Actions\Fortify;

use App\Models\Subscriber;
use App\Models\Team;
use App\Models\User;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'id' => ['required', 'integer'],
            //'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $invitation = $this->getTeamInvitation($input['id']);
        if ($input['email'] !== $invitation->email) {
            throw new InvalidArgumentException();
        }

        $user = DB::transaction(function () use ($input, $invitation) {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'email_verified_at' => now()
            ]);

            Subscriber::create([
                'user_id' => $user->id,
                'subscription_type_id' => $invitation->subscription_type_id,
                'expiration_date' =>
                    Subscriber::calculateExpirationDate($invitation->subscription_type_id, today()),
            ]);

            return tap($user, function (User $user) {
                $this->createTeam($user);
            });
        });

        $this->acceptInvitation($invitation);

        session()->flash('flash.banner', 'Registro completado. !Ya puedes comenzar a usar VX Project Field!');

        return $user;
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }

    protected function acceptInvitation($invitation): void
    {
        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();
    }

    private function getTeamInvitation(int $invitationId)
    {
        $model = Jetstream::teamInvitationModel();

        return $model::whereKey($invitationId)->firstOrFail();
    }
}
