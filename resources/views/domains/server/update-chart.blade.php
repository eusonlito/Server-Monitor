@extends ('domains.server.update-layout')

@section ('content')

<script>

const charts = new Array();

@if ($cpu)

charts.push({
    id: 'chart-{{ $row->id }}-cpu',

    config: {
        elements: {
            line: {
                tension: 1
            }
        },

        data: {
            labels: @json(array_keys($cpu)),

            datasets: [
                {
                    order: 10,

                    type: 'line',
                    label: 'CPU',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_values($cpu)),

                    parsing: {
                        xAxisKey: 'datetime',
                        yAxisKey: 'average'
                    },
                },
            ]
        },

        options: {
            plugins: {
                legend: {
                    display: false
                },
            },

            scales: {
                x: {
                    display: false,

                    ticks: {
                        autoSkip: true,
                        autoSkipPadding: 20
                    },

                    grid: {
                        display: false
                    },
                },

                y: {
                    min: 0,
                    max: 100
                }
            }
        }
    }
});

@endif

@if ($memory)

charts.push({
    id: 'chart-{{ $row->id }}-memory',

    config: {
        elements: {
            line: {
                tension: 1
            }
        },

        data: {
            labels: @json(array_keys($memory)),

            datasets: [
                {
                    order: 10,

                    type: 'line',
                    label: 'Memory',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_values($memory)),

                    parsing: {
                        xAxisKey: 'datetime',
                        yAxisKey: 'average'
                    },
                },
            ]
        },

        options: {
            plugins: {
                legend: {
                    display: false
                },
            },

            scales: {
                x: {
                    display: false,

                    ticks: {
                        autoSkip: true,
                        autoSkipPadding: 20
                    },

                    grid: {
                        display: false
                    },
                },

                y: {
                    min: 0,
                    max: {{ $memory_max }},
                    type: 'linear',
                }
            }
        }
    }
});

@endif

@if ($disk)

charts.push({
    id: 'chart-{{ $row->id }}-disk',

    config: {
        elements: {
            line: {
                tension: 1
            }
        },

        data: {
            labels: @json(array_keys($disk)),

            datasets: [
                {
                    order: 10,

                    type: 'line',
                    label: 'Disk',
                    backgroundColor: 'rgba(163, 160, 36, 0.2)',
                    borderColor: 'rgba(163, 160, 36, 1)',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: @json(array_values($disk)),

                    parsing: {
                        xAxisKey: 'datetime',
                        yAxisKey: 'average'
                    },
                },
            ]
        },

        options: {
            plugins: {
                legend: {
                    display: false
                },
            },

            scales: {
                x: {
                    display: false,

                    ticks: {
                        autoSkip: true,
                        autoSkipPadding: 20
                    },

                    grid: {
                        display: false
                    },
                },

                y: {
                    min: 0,
                    max: {{ $disk_max }},
                    type: 'linear',
                }
            }
        }
    }
});

@endif

</script>

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

<div class="grid grid-cols-12 gap-6 mt-5">
    @if ($cpu)

    <div class="col-span-12 lg:col-span-4">
        <div class="box">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ __('server-update-chart.cpu') }}
            </h2>

            <div class="p-5">
                <canvas id="chart-{{ $row->id }}-cpu"></canvas>
            </div>
        </div>
    </div>

    @endif

    @if ($memory)

    <div class="col-span-12 lg:col-span-4">
        <div class="box">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ __('server-update-chart.memory') }}
            </h2>

            <div class="p-5">
                <canvas id="chart-{{ $row->id }}-memory"></canvas>
            </div>
        </div>
    </div>

    @endif

    @if ($disk)

    <div class="col-span-12 lg:col-span-4">
        <div class="box">
            <h2 class="block border-b text-base font-medium px-5 py-2">
                {{ $disk_name }}
            </h2>

            <div class="p-5">
                <canvas id="chart-{{ $row->id }}-disk"></canvas>
            </div>
        </div>
    </div>

    @endif
</div>

@if ($disks)

<div class="box mt-5">
    <h2 class="block border-b text-base font-medium px-5 py-2">
        {{ __('server-update-chart.disks') }}
    </h2>

    <div class="p-5">
        @foreach ($disks as $each)

        <div class="mb-3">
            <div class="flex">
                <div class="flex-1 font-medium">{{ $each->mount }}</div>
                <div class="text-slate-500">@sizeHuman($each->size) - @sizeHuman($each->used) = @sizeHuman($each->available)</div>
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

@stop
