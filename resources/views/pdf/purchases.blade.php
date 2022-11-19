@extends('pdf')

@section('content-area')
<h3>@lang('All purchases')</h3>
<div class="table-responsive">
    <table class="table-listing table table-bordered table-striped table-sm">
        <thead class="thead-light">
            <tr>
                <th>@lang('#')</th>
                <th>@lang('Purchase No')</th>
                <th>@lang('Purchase Date')</th>
                <th>@lang('Supplier')</th>
                <th>@lang('Sub Total')</th>
                <th>@lang('Transport')</th>
                <th>@lang('Discount')</th>
                <th>@lang('Net Total')</th>
                <th>@lang('Total Due')</th>
                <th>@lang('Status')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $key => $purchase)
                <tr>
                    <td> {{ ++$key }} </td>
                    <td> {{ config('config.purchasePrefix') .'-'. $purchase['purchase_no'] }} </td>
                    <td> {{ \Carbon\Carbon::parse($purchase['purchase_date'])->format('d-M-Y') }} </td>
                    <td> {{ $purchase['supplier']['name'] }} </td>
                    <td>
                        @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') . $purchase['sub_total'] }}
                        @else
                            {{ $purchase['sub_total'] . config('config.currencySymbol') }}
                        @endif
                    </td>
                    <td>
                        @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') }}{{ $purchase['transport'] > 0 ?  $purchase['transport'] : 0 }}
                        @else
                            {{ $purchase['transport'] > 0 ?  $purchase['transport'] : 0 }}{{ config('config.currencySymbol') }}
                        @endif
                    </td>
                    <td>
                        @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol')  }}{{ $purchase['discount'] > 0 ? $purchase['discount'] : 0 }}
                        @else
                        {{ $purchase['discount'] > 0 ? $purchase['discount'] : 0 }} {{ config('config.currencySymbol') }}
                        @endif
                    </td>
                    <td>
                        @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') . $purchase['calculated_total'] }}
                        @else
                            {{ $purchase['calculated_total'] . config('config.currencySymbol') }}
                        @endif
                    </td>
                    <td>
                        @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') }}{{ $purchase['calculated_due'] > 0 ? $purchase['calculated_due'] : 0 }}
                        @else
                            {{ config('config.currencySymbol') }}{{ $purchase['calculated_due'] > 0 ? $purchase['calculated_due'] : 0 }}
                        @endif
                    </td>
                    <td>
                        @if ($purchase['status'])
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