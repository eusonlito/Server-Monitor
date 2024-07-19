@extends ('domains.server.update-layout')

@section ('content')

<div class="mb-3 mt-3 text-center">
    <button href="#" data-copy="#server-update-setup-contents" class="btn bg-white">@icon('clipboard', 'w-5 h-5')</button>
    <button href="#" data-select="#server-update-setup-contents" class="btn bg-white ml-5">@icon('check-square', 'w-5 h-5')</button>
</div>

<div class="box p-10">
    <pre id="server-update-setup-contents">
curl -H "Authorization: Bearer {{ $row->auth }}" -o server-monitor {{ route('server.measure') }}

chmod 755 server-monitor.sh

./server-monitor.sh</pre>
</div>

@stop
