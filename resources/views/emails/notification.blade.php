@component('mail::message')
# {{ $greeting }}
{!! $body !!}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
