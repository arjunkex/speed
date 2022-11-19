@extends('pdf')

@section('content-area')
    <h3>@lang('All invoices')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Invoice No')</th>
                    <th>@lang('Invoice Date')</th>
                    <th>@lang('Client')</th>
                    <th>@lang('Subtotal')</th>
                    <th>@lang('Transport')</th>
                    <th>@lang('Discount')</th>
                    <th>@lang('Tax')</th>
                    <th>@lang('Net Total')</th>
                    <th>@lang('Due')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoices as $key => $invoice)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ config('config.invoicePrefix') . '-' . $invoice['invoice_no'] }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($invoice['invoice_date'])->format('d-M-Y') }}
                        </td>
                        <td>{{ $invoice['client']['name'] }}</td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . $invoice['sub_total'] }}
                            @else
                                {{ $invoice['sub_total'] . config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') }} {{ $invoice['transport'] > 0 ? $invoice['transport'] : 0 }}
                            @else
                                {{ $invoice['transport'] > 0 ? $invoice['transport'] : 0 }} {{ config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') }} {{ $invoice['discount'] > 0 ? $invoice['discount'] : 0 }}
                            @else
                                {{ $invoice['discount'] > 0 ? $invoice['discount'] : 0 }} {{ config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') }} {{ $invoice['calculated_tax'] > 0 ? $invoice['calculated_tax'] : 0 }}
                            @else
                                {{ $invoice['calculated_tax'] > 0 ? $invoice->total_tax : 0 }} {{ config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . $invoice['calculated_total'] }}
                            @else
                                {{ $invoice['calculated_total'] . config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') }} {{ $invoice['calculated_due'] > 0 ? $invoice['calculated_due'] : 0 }}
                            @else
                            {{ $invoice['calculated_due'] > 0 ? $invoice['calculated_due'] : 0 }} {{ config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if ($invoice['status'])
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
