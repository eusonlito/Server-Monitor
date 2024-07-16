@extends ('layouts.in')

@section ('body')

<div class="box flex items-center px-5">
    <div class="nav nav-tabs flex overflow-auto whitespace-nowrap" role="tablist">
        <a href="{{ route('server.update', $row->id) }}" class="p-4 {{ ($ROUTE === 'server.update') ? 'active' : '' }}" role="tab">{{ $row->name }}</a>
        <a href="{{ route('server.update.chart', $row->id) }}" class="p-4 {{ ($ROUTE === 'server.update.chart') ? 'active' : '' }}" role="tab">{{ __('server-update.header.charts') }}</a>
        <a href="{{ route('server.update.measure', $row->id) }}" class="p-4 {{ ($ROUTE === 'server.update.measure') ? 'active' : '' }}" role="tab">{{ __('server-update.header.measures') }}</a>

        @if ($ROUTE === 'server.update.measure.update')

        @if ($previous)

        <a href="{{ route('server.update.measure.update', [$row->id, $previous]) }}" class="p-4" role="tab">&laquo; {{ __('server-update.header.previous') }}</a>

        @else

        <span class="text-gray-300 p-4" role="tab">&laquo; {{ __('server-update.header.previous') }}</span>

        @endif

        <a href="{{ route('server.update.measure.update', [$row->id, $measure->id]) }}" class="p-4 {{ str_starts_with($ROUTE, 'server.update.measure') ? 'active' : '' }}" role="tab">@dateLocal($measure->created_at)</a>

        @if ($next)

        <a href="{{ route('server.update.measure.update', [$row->id, $next]) }}" class="p-4" role="tab">{{ __('server-update.header.next') }} &raquo;</a>

        @else

        <span class="text-gray-300 p-4" role="tab">{{ __('server-update.header.next') }} &raquo;</span>

        @endif

        @endif
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane active" tab="tabpanel">
        @yield('content')
    </div>
</div>

@stop
