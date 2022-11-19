@extends('pdf')

@section('content-area')
    <h3>@lang('All product list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Item Model')</th>
                    <th>@lang('Stock Qty')</th>
                    <th>@lang('Avg. Puchase Price')</th>
                    <th>@lang('Regular Price')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>
                            {{ $product['pro_sub_category']['name'] }}<br />
                            [{{ config('config.proSubCatPrefix') . '-' . $product['pro_sub_category']['code'] }}]
                        </td>
                        <td>{{ config('config.productPrefix') . '-' . $product['code'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['model'] }}</td>
                        <td>{{ $product['inventory_count'] }}</td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . $product['purchase_price'] }}
                            @else
                                {{ $product['purchase_price'] . config('config.currencySymbol') }}
                            @endif
                        </td>
                        <td>
                            @if(config('config.currencyPosition')  == 'left')
                                {{ config('config.currencySymbol') . $product['regular_price'] }}
                            @else
                                {{ $product['regular_price'] . config('config.currencySymbol') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
