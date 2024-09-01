@extends ('domains.server.update-layout')

@section ('content')

<script>

const charts = new Array();

@if ($labels)

charts.push({
    id: 'chart-{{ $row->id }}-disks',

    config: {
        elements: {
            line: {
                tension: 1
            }
        },

        data: {
            labels: @json($labels),

            datasets: [
                @foreach ($groups as $mount => $disk)

                {
                    order: 10,

                    type: 'line',
                    label: '{{ $mount }}',
                    backgroundColor: '{{ helper()->stringToRGBA($mount, 0.2) }}',
                    borderColor: '{{ helper()->stringToRGBA($mount, 1) }}',
                    steppedLine: false,
                    fill: false,
                    pointRadius: 0,
                    pointHitRadius: 5,
                    borderWidth: 1.5,
                    data: {!! json_encode(array_map(static fn ($value) => explode(' ', $value, 2)[0], $disk)) !!},
                    dataOriginal: @json($disk),
                },

                @endforeach
            ],
        },

        options: {
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
                    max: 100,
                    type: 'linear',
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => value + '%'
                    }
                }
            },

            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => context.dataset.dataOriginal[context.label]
                    }
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

<div class="box mt-5">
    <h2 class="block border-b text-base font-medium px-5 py-2">
        {{ __('server-update-measure-disk.disks') }}
    </h2>

    <div class="p-5">
        <canvas id="chart-{{ $row->id }}-disks"></canvas>
    </div>
</div>

@stop
