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
  <link rel="preconnect" href="//fonts.googleapis.com">
  <link rel="preconnect" href="//fonts.gstatic.com" crossorigin>
  <link href="//fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400&display=swap"
        rel="stylesheet">
  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body class="hold-transition layout-footer-fixed">
<div id="app">
  <div class="container-fluid">
    <div class="row no-gutter">
      <!-- The image half -->
      <div class="col-md-6 d-none d-md-flex bg-image"></div>
      <!-- The content half -->
      <div class="col-md-6 bg-light">
        <div class="auth-wrapper d-flex align-items-center py-5">
          <!-- Demo content-->
          <div class="container">
            <div class="row">
              <div class="col-md-12 col-lg-10 col-xl-8 mx-auto">
                @if($message)
                  <h3 class="text-center @if($success) text-success @else text-danger @endif">{{ $message }}</h3>
                @endif

                <div class="text-center mt-3">
                  @if(isset($domain))
                    <a href="{{ $domain }}" class="btn btn-success">Go to your domain</a>
                  @else
                    <a href="{{ url('/') }}" class="btn btn-danger">Go back</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <!-- End -->
        </div>
      </div>
      <!-- End -->
    </div>
  </div>
</div>

{{-- Global configuration object --}}
<script>
  window.config = @json($config);
</script>

</body>

</html>
