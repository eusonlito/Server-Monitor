@extends ('domains.server.update-layout')

@section ('content')

<div class="overflow-auto scroll-visible header-sticky">
    <table class="table table-report sm:mt-2 font-medium font-semibold text-center whitespace-nowrap">
        <thead>
            <tr>
                <th class="text-left">{{ __('measure.db.memory') }}</th>
                <th class="text-left">{{ __('measure.db.cpu') }}</th>
                <th class="text-left">{{ __('measure.db.disk') }}</th>
                <th class="text-left">{{ __('measure.db.app-cpu') }}</th>
                <th class="text-left">{{ __('measure.db.app-memory') }}</th>
                <th>{{ __('measure.db.created_at') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($measures as $each)

            @php ($link = route('server.update.measure.update', [$row->id, $each->id]))

            <tr>
                <td class="text-left">
                    <a href="{{ $link }}" class="block">@sizeHuman($each->memory_used) / @sizeHuman($each->memory_total) - {{ $each->memory_percent }}%</a>
                </td>

                <td class="text-left">
                    <a href="{{ $link }}" class="block">{{ $each->cpu_load_1 }} / {{ $each->cores }} - {{ $each->cpu_percent }}%</a>
                </td>

                <td class="text-left">
                    @if ($disk = $each->disk)

                    <a href="{{ $link }}" class="block">@sizeHuman($disk->used) / @sizeHuman($disk->size) - {{ $disk->percent }}%</a>

                    @endif
                </td>

                <td class="text-left">
                    @if ($app = $each->appCpu)

                    <a href="{{ $link }}" class="block">{{ $app->command }} | {{ $app->cpu_load }} - {{ $app->cpu_percent }}%</a>

                    @endif
                </td>

                <td class="text-left">
                    @if ($app = $each->appMemory)

                    <a href="{{ $link }}" class="block">{{ $app->command }} | @sizeHuman($app->memory_resident) - {{ $app->memory_percent }}%</a>

                    @endif
                </td>

                <td data-table-sort-value="{{ $each->created_at }}"><span class="block">@dateLocal($each->created_at)</span></td>
            </tr>

            @endforeach
        </tbody>
    </table>

    {{ $measures->withQueryString()->links() }}
</div>

@stop
