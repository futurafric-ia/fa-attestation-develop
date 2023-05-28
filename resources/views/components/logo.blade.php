@php
$logo = isset($logged_in_user) && $logged_in_user->isBroker()
    ? $logged_in_user->currentBroker->profile_photo_url
    : url('static/images/logo.png');
@endphp

<img {{ $attributes }} src="{{ $logo }}" alt="Logo Saham">
