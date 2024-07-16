@extends ('domains.server.update-layout')

@section ('content')

<form method="get">
    <div class="box p-5 mt-3">
        <div class="lg:flex">
            <div class="flex-1 px-2 mt-2 lg:mt-0">
                <input type="text" name="date_start" class="form-control form-control-lg" value="{{ $REQUEST->input('date_start') }}" placeholder="{{ __('common.filters.date_start') }}" data-datepicker data-change-submit />
            </div>

            <div class="flex-1 px-2 mt-2 lg:mt-0">
                <input type="text" name="date_end" class="form-control form-control-lg" value="{{ $REQUEST->input('date_end') }}" placeholder="{{ __('common.filters.date_end') }}" data-datepicker data-change-submit />
            </div>
        </div>
    </div>
</form>

<div class="overflow-auto scroll-visible header-sticky">
    <table class="table table-report sm:mt-2 font-medium font-semibold text-center whitespace-nowrap">
        <thead>
            <tr>
                @thOrder('memory_percent', __('measure.db.memory'))
                @thOrder('cpu_percent', __('measure.db.cpu'))
                @thOrder('measure_disk', __('measure.db.disk'))
                <th>{{ __('measure.db.app-cpu') }}</th>
                <th>{{ __('measure.db.app-memory') }}</th>
                @thOrder('created_at', __('measure.db.created_at'))
            </tr>
        </thead>

        <tbody>
            @foreach ($measures as $each)

            @php ($link = route('server.update.measure.update', [$row->id, $each->id]))

            <tr>
                <td>
                    <a href="{{ $link }}" class="block">@sizeHuman($each->memory_used) / @sizeHuman($each->memory_total) - {{ $each->memory_percent }}%</a>
                </td>

                <td>
                    <a href="{{ $link }}" class="block">{{ $each->cpu_load_1 }} / {{ $each->cores }} - {{ $each->cpu_percent }}%</a>
                </td>

                <td>
                    @if ($disk = $each->disk)

                    <a href="{{ $link }}" class="block">@sizeHuman($disk->used) / @sizeHuman($disk->size) - {{ $disk->percent }}%</a>

                    @endif
                </td>

                <td>
                    @if ($app = $each->appCpu)

                    <a href="{{ $link }}" class="block">{{ $app->command }} | {{ $app->cpu_load }} - {{ $app->cpu_percent }}%</a>

                    @endif
                </td>

                <td>
                    @if ($app = $each->appMemory)

                    <a href="{{ $link }}" class="block">{{ $app->command }} | @sizeHuman($app->memory_resident) - {{ $app->memory_percent }}%</a>

                    @endif
                </td>

                <td data-table-sort-value="{{ $each->created_at }}" class="text-center">
                    <a href="{{ $link }}" class="block">@dateLocal($each->created_at)</a>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

    {{ $measures->withQueryString()->links() }}
</div>

@stop
