@extends ('domains.server.update-layout')

@section ('content')


<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-6">
        <div class="box mt-5">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ __('server-update-measure-update.cpu') }}
            </h2>

            <div class="p-5">
                <div class="flex">
                    <div class="font-medium">
                        {{ $measure->cpu_load_1 }}
                        |
                        {{ $measure->cpu_load_5 }}
                        |
                        {{ $measure->cpu_load_15 }}
                    </div>

                    @progressbar($measure->cpu_percent, 'flex-1 h-5 ml-5')

                    <div class="font-medium ml-5">
                        {{ $measure->cpu_percent }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="box mt-5">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ __('server-update-measure-update.memory') }}
            </h2>

            <div class="p-5">
                <div class="flex">
                    <div class="font-medium">
                        @sizeHuman($measure->memory_used)
                        /
                        @sizeHuman($measure->memory_total)
                    </div>

                    @progressbar($measure->memory_percent, 'flex-1 h-5 ml-5')

                    <div class="font-medium ml-5">
                        {{ $measure->memory_percent }}%
                    </div>
                </div>
            </div>
        </div>

        @if ($disks)

        <div class="box mt-5">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ __('server-update-measure-update.disks') }}
            </h2>

            <div class="p-5">
                @foreach ($disks as $each)

                <div class="mb-3">
                    <div class="flex">
                        <div class="flex-1 font-medium">{{ $each->mount }}</div>
                        <div class="text-slate-500">@sizeHuman($each->used) / @sizeHuman($each->size)</div>
                    </div>

                    <div class="flex mt-2 items-center">
                        @progressbar($each->percent, 'flex-1 h-3')

                        <div class="text-slate-400 ml-3">{{ $each->percent }}%</div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>

        @endif
    </div>

    @if ($apps)

    <div class="col-span-12 lg:col-span-6">
        <div class="box mt-5">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ __('server-update-measure-update.apps') }}
            </h2>

            <div class="p-5">
                <div class="overflow-auto scroll-visible header-sticky">
                    <table id="server-list-table" class="table whitespace-nowrap text-right" data-table-sort>
                        <thead>
                            <tr>
                                <th class="text-left">{{ __('measure-app.db.command') }}</th>
                                <th class="text-left">{{ __('measure-app.db.user') }}</th>
                                <th>{{ __('measure-app.db.memory_virtual') }}</th>
                                <th>{{ __('measure-app.db.memory_resident') }}</th>
                                <th>{{ __('measure-app.db.cpu_load') }}</th>
                                <th>{{ __('measure-app.db.time') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($apps as $each)

                            <tr>
                                <td class="text-left">{{ $each->command }}</td>
                                <td class="text-left">{{ $each->user }}</td>
                                <td data-table-sort-value="{{ $each->memory_virtual }}">@sizeHuman($each->memory_virtual)</td>
                                <td data-table-sort-value="{{ $each->memory_resident }}">@sizeHuman($each->memory_resident)</td>
                                <td>{{ $each->cpu_load }}</td>
                                <td data-table-sort-value="{{ $each->time }}">@secondsToTime($each->time)</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>

@stop
