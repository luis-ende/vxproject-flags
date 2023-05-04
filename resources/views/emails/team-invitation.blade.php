@component('mail::message')
¡Has sido invitado a unirte a la comunidad de suscriptores!
{{--    {{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name]) }}--}}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{--{{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the team invitation:') }}--}}

Si no tienes una cuenta, puedes crear una haciendo clic en el botón de abajo. Después de crear una cuenta puedes hacer clic en el botón de aceptación de la invitación en este correo electrónico para aceptar la invitación a la comunidad:

@component('mail::button', ['url' => route('register')])
{{ __('Create Account') }}
@endcomponent

{{--{{ __('If you already have an account, you may accept this invitation by clicking the button below:') }}--}}
Si ya tienes una cuenta puedes aceptar esta invitación haciendo clic en el botón de abajo:

@else
{{--{{ __('You may accept this invitation by clicking the button below:') }}--}}
Puedes aceptar esta invitación haciendo clic en el botón de abajo:
@endif


@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{--{{ __('If you did not expect to receive an invitation to this team, you may discard this email.') }}--}}
Si no esperabas recibir una invitación para esta comunidad puedes descartar este correo electrónico.
@endcomponent
