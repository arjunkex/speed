@if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('tenant.find-domain') }}">
  @csrf

  <!-- Domain -->
  <div class="mt-4">
    <label for="domain">{{ __('Subdomain (Ex: shop)') }}</label>

    <div class="flex items-stretch mt-1">
      <input id="domain"
             class="block w-full rounded-l-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
             type="text" name="domain" value="{{ old('domain') }}" required autofocus/>

      <div class="bg-gray-100 border border-gray-300 px-3 flex items-center rounded-r">
        <span class="text-gray-500 sm:text-sm"> {{ config('tenancy.domain') }} </span>
      </div>
    </div>
  </div>

  <div class="flex items-center justify-end mt-4">
    <button class="ml-4">
      {{ __('Find') }}
    </button>
  </div>
</form>
