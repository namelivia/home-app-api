@component('mail::message')
# Movement detected

Hey! I've just detected some movement

@component('mail::button', ['url' => 'https://nosferatu.namelivia.com/home#/camera'])
Check it out!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
