@extends('pdf')

@section('content-area')
    <h3>@lang('All tenant list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Tenant ID')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Company')</th>
                    <th>@lang('Domain')</th>
                    <th>@lang('Is Banned')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $tenant)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td>{{ $tenant->name }}</td>
                        <td>{{ $tenant->id }}</td>
                        <td>{{ $tenant->email }}</td>
                        <td>{{ $tenant->company }}</td>
                        <td>{{ $tenant->domain }}</td>
                        <td>
                            @if ($tenant->is_banned)
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
