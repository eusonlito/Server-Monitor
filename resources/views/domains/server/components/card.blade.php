<div class="box">
    <div class="flex border-b text-base font-medium px-5 py-2 lg:py-3 items-center">
        <a href="{{ route('server.update.chart', $server->id) }}" class="flex-1 align-middle">
            {{ $server->name }}
        </a>

        @if ($draggable)

        <div class="text-gray-400" data-draggable-handle>
            @icon('align-justify')
        </div>

        @endif
    </div>

    @if ($measure)

    <div class="lg:flex font-medium text-center whitespace-nowrap overflow-auto">
        <div class="flex-1 flex lg:block p-2 lg:p-4 items-center content-center">
            <div class="lg:pb-2 mr-3 lg:mr-0">
                @icon('zap')
            </div>

            <div class="lg:pb-2 mr-3 lg:mr-0">
                <span class="inline-block align-middle">@sizeHuman($measure->memory_used)</span>
                <span class="inline-block align-middle">/</span>
                <span class="inline-block align-middle">@sizeHuman($measure->memory_total)</span>
            </div>

            <div class="flex-1 flex items-center">
                <div class="mr-3">
                    <span class="inline-block align-middle">{{ $measure->memory_percent }}%</span>
                </div>

                @progressbar($measure->memory_percent, 'flex-1 h-5')
            </div>
        </div>

        <div class="flex-1 flex lg:block p-2 lg:p-4 items-center content-center">
            <div class="lg:pb-2 mr-3 lg:mr-0">
                @icon('cpu')
            </div>

            <div class="lg:pb-2 mr-3 lg:mr-0">
                <span class="inline-block align-middle">{{ $measure->cpu_load_1 }} {{ $measure->cpu_load_5 }} {{ $measure->cpu_load_15 }}</span>
                <span class="inline-block align-middle">/</span>
                <span class="inline-block align-middle">{{ $measure->cores }}</span>
            </div>

            <div class="flex-1 flex items-center">
                <div class="mr-3">
                    <span class="inline-block align-middle">{{ $measure->cpu_percent }}%</span>
                </div>

                @progressbar($measure->cpu_percent, 'flex-1 h-5')
            </div>
        </div>

        @if ($disk)

        <div class="flex-1 flex lg:block p-2 lg:p-4 items-center content-center">
            <div class="lg:pb-2 mr-3 lg:mr-0">
                @icon('hard-drive')
            </div>

            <div class="lg:pb-2 mr-3 lg:mr-0">
                <span class="inline-block align-middle">@sizeHuman($disk->used)</span>
                <span class="inline-block align-middle">/</span>
                <span class="inline-block align-middle">@sizeHuman($disk->size)</span>
            </div>

            <div class="flex-1 flex items-center">
                <div class="mr-3">
                    <span class="inline-block align-middle">{{ $disk->percent }}%</span>
                </div>

                @progressbar($disk->percent, 'flex-1 h-5 ml-2')
            </div>
        </div>

        @endif

        @if ($appCpu)

        <div class="flex-1 flex lg:block p-2 lg:p-4 items-center content-center">
            <div class="lg:pb-2 mr-3 lg:mr-0">
                @icon('terminal')
                @icon('cpu', 'hidden lg:inline')
            </div>

            <div class="lg:pb-2 mr-3 lg:mr-0">
                <span class="inline-block align-middle">{{ $appCpu->command }}</span>
                <span class="inline-block align-middle">|</span>
                <span class="inline-block align-middle">{{ $appCpu->cpu_load }}</span>
            </div>

            <div class="flex-1 flex items-center">
                <div class="mr-3">
                    <span class="inline-block align-middle">{{ $appCpu->cpu_percent }}%</span>
                </div>

                @progressbar($appCpu->cpu_percent, 'flex-1 h-5 ml-2')
            </div>
        </div>

        @endif

        @if ($appMemory)

        <div class="flex-1 flex lg:block p-2 lg:p-4 items-center content-center">
            <div class="lg:pb-2 mr-3 lg:mr-0">
                @icon('terminal')
                @icon('zap', 'hidden lg:inline')
            </div>

            <div class="lg:pb-2 mr-3 lg:mr-0">
                <span class="inline-block align-middle">{{ $appMemory->command }}</span>
                <span class="inline-block align-middle">|</span>
                <span class="inline-block align-middle">@sizeHuman($appMemory->memory_resident)</span>
            </div>

            <div class="flex-1 flex items-center">
                <div class="mr-3">
                    <span class="inline-block align-middle">{{ $appMemory->memory_percent }}%</span>
                </div>

                @progressbar($appMemory->memory_percent, 'flex-1 h-5 ml-2')
            </div>
        </div>

        @endif
    </div>

    @endif
</div>
