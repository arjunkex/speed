@extends('pdf')

@section('content-area')
    <h3>@lang('All increments list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Emp ID')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Basic Salary')</th>
                    <th>@lang('Increment Amount')</th>
                    <th>@lang('Present Salary')</th>
                    <th>@lang('Increment Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salIncrements as $key => $salIncrement)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $salIncrement['employee']['name'] }}</td>
                        <td>
                          {{ config('config.employeePrefix') .'-'. $salIncrement['employee']['emp_id'] }}
                        </td>
                        <td>{{ $salIncrement['reason'] }}</td>
                        <td>
                          @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') . $salIncrement['increment_amount'] }}
                          @else
                            {{ $salIncrement['increment_amount'] . config('config.currencySymbol') }}
                          @endif
                        </td>
                        <td>
                          @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') . $salIncrement['employee']['salary'] }}
                          @else
                            {{ $salIncrement['employee']['salary'] . config('config.currencySymbol') }}
                          @endif
                        </td>
                        <td>
                          @if(config('config.currencyPosition')  == 'left')
                            {{ config('config.currencySymbol') . $salIncrement['employee']['calculated_salary']}}
                          @else
                            {{ $salIncrement['employee']['calculated_salary'] . config('config.currencySymbol') }}
                          @endif
                        </td>
                        <td>{{ $salIncrement['increment_date'] }}</td>
                        <td>
                            @if ($salIncrement['status'])
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
