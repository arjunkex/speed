@component('mail::message')
  # {{ $greeting }}
  {!! $body !!}
  Thanks,<br>
  {{ config('app.name') }}

  <div class="footer">
    <div>
      © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
    </div>
    <div>
      <a href="{{ $url }}">{{ __('Unsubscribe') }}</a>
    </div>
  </div>
@endcomponent
