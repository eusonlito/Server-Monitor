@extends ('layouts.in')

@section ('body')

<div class="grid grid-cols-12 gap-6" data-draggable="servers" data-draggable-url="{{ route('server.order') }}">
    @foreach ($servers as $server)

    <div class="col-span-12 lg:col-span-6" data-draggable-element="{{ $server->id }}">
        <x-server-card :server="$server" />
    </div>

    @endforeach
</div>

@stop
