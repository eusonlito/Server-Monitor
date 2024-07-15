@extends ('layouts.in')

@section ('body')

<div class="box flex items-center px-5">
    <div class="nav nav-tabs flex overflow-auto whitespace-nowrap" role="tablist">
        <a href="{{ route('server.update', $row->id) }}" class="p-4 {{ ($ROUTE === 'server.update') ? 'active' : '' }}" role="tab">{{ $row->name }}</a>
    </div>

    <div class="nav nav-tabs flex overflow-auto whitespace-nowrap" role="tablist">
        <a href="{{ route('server.update.chart', $row->id) }}" class="p-4 {{ str_starts_with($ROUTE, 'server.update.chart') ? 'active' : '' }}" role="tab">{{ __('server-update.header.charts') }}</a>
    </div>

    <div class="nav nav-tabs flex overflow-auto whitespace-nowrap" role="tablist">
        <a href="{{ route('server.update.measure', $row->id) }}" class="p-4 {{ str_starts_with($ROUTE, 'server.update.measure') ? 'active' : '' }}" role="tab">{{ __('server-update.header.measures') }}</a>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane active" tab="tabpanel">
        @yield('content')
    </div>
</div>

@stop
