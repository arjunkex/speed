@extends('pdf')

@section('content-area')
    <h3>@lang('Non invoice add balances')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Bank Name')</th>
                    <th>@lang('Account Number')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($balances as $key => $balance)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $balance['cashbook_account']['bank_name'] }}</td>
                        <td>{{ $balance['cashbook_account']['account_number'] }}</td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . $balance['amount'] }}
                            @else
                                {{ $balance['amount'] . config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($balance['transaction_date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($balance['status'])
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
