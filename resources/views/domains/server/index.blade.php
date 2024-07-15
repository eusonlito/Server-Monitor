@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('server-index.filter') }}" data-table-search="#server-list-table" />
        </div>

        <div class="sm:ml-4 mt-2 sm:mt-0 bg-white">
            <a href="{{ route('server.create') }}" class="btn form-control-lg whitespace-nowrap">{{ __('server-index.create') }}</a>
        </div>
    </div>
</form>

<div class="overflow-auto scroll-visible header-sticky">
    <table id="server-list-table" class="table table-report sm:mt-2 font-medium font-semibold text-center whitespace-nowrap" data-table-pagination="server-list-table-pagination" data-table-sort>
        <thead>
            <tr>
                <th class="text-left">{{ __('server-index.name') }}</th>
                <th class="text-left">{{ __('measure.db.memory') }}</th>
                <th class="text-left">{{ __('measure.db.cpu') }}</th>
                <th class="text-left">{{ __('measure.db.disk') }}</th>
                <th class="text-left">{{ __('measure.db.app-cpu') }}</th>
                <th class="text-left">{{ __('measure.db.app-memory') }}</th>
                <th>{{ __('server-index.enabled') }}</th>
                <th>{{ __('server-index.dashboard') }}</th>
                <th>{{ __('server-index.created_at') }}</th>
                <th>{{ __('server-index.updated_at') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $row)

            @php ($link = route('server.update', $row->id))

            <tr>
                <td class="text-left"><a href="{{ $link }}" class="block">{{ $row->name }}</a></td>

                <td class="text-left">
                    @if ($measure = $row->measure)

                    @sizeHuman($measure->memory_used) / @sizeHuman($measure->memory_total) - {{ $measure->memory_percent }}%

                    @endif
                </td>

                <td class="text-left">
                    @if ($measure)

                    {{ $measure->cpu_load_1 }} / {{ $measure->cores }} - {{ $measure->cpu_percent }}%

                    @endif
                </td>

                <td class="text-left">
                    @if ($disk = $measure?->disk)

                    @sizeHuman($disk->used) / @sizeHuman($disk->size) - {{ $disk->percent }}%

                    @endif
                </td>

                <td class="text-left">
                    @if ($app = $measure?->appCpu)

                    {{ $app->command }} | {{ $app->cpu_load }} - {{ $app->cpu_percent }}%

                    @endif
                </td>

                <td class="text-left">
                    @if ($app = $measure?->appMemory)

                    {{ $app->command }} | @sizeHuman($app->memory_resident) - {{ $app->memory_percent }}%

                    @endif
                </td>

                <td data-table-sort-value="{{ intval($row->enabled) }}">@status($row->enabled)</td>
                <td data-table-sort-value="{{ intval($row->dashboard) }}">@status($row->dashboard)</td>
                <td data-table-sort-value="{{ $row->created_at }}"><a href="{{ $link }}" class="block">@dateLocal($row->created_at)</a></td>
                <td data-table-sort-value="{{ $row->updated_at }}"><a href="{{ $link }}" class="block">@dateLocal($row->updated_at)</a></td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <ul id="server-list-table-pagination" class="pagination justify-end"></ul>
</div>

@stop
