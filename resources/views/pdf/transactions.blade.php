@extends('pdf')

@section('content-area')
    <h3>@lang('Transaction of account: ') {{ $transactions[0]['cashbook_account']['account_number'] }}</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('User')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $key => $transaction)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $transaction['reason'] }}</td>
                        <td>{{ $transaction['transaction_date'] }}</td>
                        <td>
                            @if($transaction['type'] == 1)
                                <span class="badge bg-primary">@lang('Credit')</span>
                            @else
                                <span class="badge bg-danger">@lang('Debit')</span>
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . $transaction['amount'] }}
                            @else
                                {{ $transaction['amount'] . config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if ($transaction['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td>{{ $transaction['user']['name'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
