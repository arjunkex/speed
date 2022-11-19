@extends('pdf')

@section('content-area')
    <h3>@lang('All loan authorities')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Contact Number')</th>
                    <th>@lang('CC Limit')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loanAuthorities as $key => $authority)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $authority['name'] }}</td>
                        <td>{{ $authority['email'] }}</td>
                        <td>{{ $authority['contact_number'] }}</td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . round($authority['cc_limit'], 2) }}
                            @else
                                {{ round($authority['cc_limit'], 2) . config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if ($authority['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
