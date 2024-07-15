<div class="box p-5 mt-5">
    <div class="p-2">
        <label for="server-name" class="form-label">{{ __('server-create.name') }}</label>
        <input type="text" name="name" class="form-control form-control-lg" id="server-name" value="{{ $REQUEST->input('name') }}" required>
    </div>

    <div class="p-2">
        <label for="server-ip" class="form-label">{{ __('server-create.ip') }}</label>
        <input type="text" name="ip" class="form-control form-control-lg" id="server-ip" value="{{ $REQUEST->input('ip') }}">
    </div>

    <div class="p-2">
        <label for="server-auth" class="form-label">{{ __('server-create.auth') }}</label>

        <div class="input-group">
            <input type="password" name="auth" class="form-control form-control-lg" id="server-auth" value="{{ $REQUEST->input('auth') }}" autocomplete="off" />

            <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.show') }}" data-password-show="#server-auth" tabindex="-1">@icon('eye', 'w-5 h-5')</button>
            <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.copy') }}" data-copy="#server-auth" tabindex="-1">@icon('clipboard', 'w-5 h-5')</button>
            <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.generate') }}" data-password-generate="#server-auth" data-password-generate-format="uuid" tabindex="-1">@icon('refresh-cw', 'w-5 h-5')</button>
            <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.reset') }}" data-input-default="#server-auth" data-tabindex="-1">@icon('skip-back', 'w-5 h-5')</button>
        </div>
    </div>
</div>

<div class="box p-5 mt-5">
    <div class="p-2">
        <div class="form-check">
            <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="server-enabled" {{ $REQUEST->input('enabled') ? 'checked' : '' }}>
            <label for="server-enabled" class="form-check-label">{{ __('server-create.enabled') }}</label>
        </div>
    </div>

    <div class="p-2">
        <div class="form-check">
            <input type="checkbox" name="dashboard" value="1" class="form-check-switch" id="server-dashboard" {{ $REQUEST->input('dashboard') ? 'checked' : '' }}>
            <label for="server-dashboard" class="form-check-label">{{ __('server-create.dashboard') }}</label>
        </div>
    </div>
</div>
