@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('central.login') }}">
  @csrf

  <!-- Email Address -->
  <div class="mt-4">
    <label for="email">{{ __('Email') }}</label>

    <input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ old('email') }}" required/>
  </div>

  <!-- Password -->
  <div class="mt-4">
    <label for="password">{{ __('Password') }}</label>

    <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="password"/>
  </div>

  <div class="flex items-center justify-end mt-4">
    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('tenant.register.index') }}">
      {{ __('Register here!') }}
    </a>

    <button class="ml-4">
      {{ __('Login') }}
    </button>
  </div>
</form>
