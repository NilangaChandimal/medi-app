@component('mail::message')
# Reset Your Admin Password

We received a request to reset your password. Click the button below to set a new password.

@component('mail::button', ['url' => route('admin.password.reset', ['token' => $token, 'email' => $email])])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,
{{ config('app.name') }}
@endcomponent
