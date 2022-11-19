@php
    $config = [
        'appName' => config('app.name'),
        'locale' => ($locale = app()->getLocale()),
        'locales' => config('app.locales'),
        'githubAuth' => config('services.github.client_id'),
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }}</title>
    <link rel="icon" href='{{ global_asset('images/'.config('config.favicon')) }}'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body class="hold-transition layout-footer-fixed">
<div id="app"></div>

{{-- Global configuration object --}}
<script>
  window.config = @json($config);
  window.tenant = {
    data: @json(tenant()),
    on_trial: @json(tenant()->on_trial),
    plan: @json($tenantPlan),
    subscriptionLimit: null,
  }
  window.stripe_key = "{{ config('services.stripe.key') }}";
</script>

{{-- Load the application scripts --}}

<script src="{{ mix('/js/tenant.js') }}"></script>

</body>

</html>
