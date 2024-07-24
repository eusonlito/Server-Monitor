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

<div class="box mt-5">
    <div class="p-5">
        <div class="overflow-auto scroll-visible header-sticky">
            <table id="server-list-table" class="table whitespace-nowrap text-right" data-table-sort>
                <thead>
                    <tr>
                        <th>{{ __('measure-app.db.command') }}</th>
                        <th>{{ __('measure-app.db.user') }}</th>
                        <th>{{ __('measure-app.db.memory_resident_max') }}</th>
                        <th>{{ __('measure-app.db.memory_resident_avg') }}</th>
                        <th>{{ __('measure-app.db.cpu_load_max') }}</th>
                        <th>{{ __('measure-app.db.cpu_load_avg') }}</th>
                        <th>{{ __('measure-app.db.time') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($apps as $each)

                    <tr>
                        <td>{{ $each->command }}</td>
                        <td>{{ $each->user }}</td>
                        <td data-table-sort-value="{{ $each->memory_resident_max }}">
                            <a href="{{ route('server.update.measure.update', [$row->id, $each->memory_percent_max_measure_id]) }}">@sizeHuman($each->memory_resident_max) ({{ $each->memory_percent_max }} %)</a>
                        </td>
                        <td data-table-sort-value="{{ $each->memory_resident_avg }}">@sizeHuman($each->memory_resident_avg) ({{ $each->memory_percent_avg }} %)</td>
                        <td data-table-sort-value="{{ $each->cpu_load_max }}">
                            <a href="{{ route('server.update.measure.update', [$row->id, $each->cpu_percent_max_measure_id]) }}">{{ $each->cpu_load_max }} ({{ $each->cpu_percent_max }} %)</a>
                        </td>
                        <td data-table-sort-value="{{ $each->cpu_load_avg }}">{{ $each->cpu_load_avg }} ({{ $each->cpu_percent_avg }} %)</td>
                        <td data-table-sort-value="{{ $each->time }}">@timeHuman($each->time)</td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
