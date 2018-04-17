@component('mail::message')
# Please Confirm your email

Just one more step to prevent spam and to keep you safe

@component('mail::button', ['url' => ''])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
