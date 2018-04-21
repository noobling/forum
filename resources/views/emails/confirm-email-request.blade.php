@component('mail::message')
# Please Confirm your email

Just one more step to prevent spam and to keep you safe

@component('mail::button', ['url' => url('/register/confirmation?token='.$user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
