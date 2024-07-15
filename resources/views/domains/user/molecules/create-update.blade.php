<div class="box p-5 mt-5">
    <div class="p-2">
        <label for="user-name" class="form-label">{{ __('user-create.name') }}</label>
        <input type="text" name="name" class="form-control form-control-lg" id="user-name" value="{{ $REQUEST->input('name') }}" required>
    </div>

    <div class="p-2">
        <label for="user-email" class="form-label">{{ __('user-create.email') }}</label>
        <input type="email" name="email" class="form-control form-control-lg" id="user-email" value="{{ $REQUEST->input('email') }}" required>
    </div>

    <div class="p-2">
        <label for="user-password" class="form-label">{{ __('user-create.password') }}</label>

        <div class="input-group">
            <input type="password" name="password" class="form-control form-control-lg" id="user-password" value="{{ $REQUEST->input('password') }}" autocomplete="off" />
            <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.show') }}" data-password-show="#user-password" tabindex="-1">@icon('eye', 'w-5 h-5')</button>
        </div>
    </div>

    <div class="p-2">
        <x-select name="language_id" :options="$language_options" :label="__('user-create.language')" required></x-select>
    </div>
</div>

<div class="box p-5 mt-5">
    <div class="p-2">
        <div class="form-check">
            <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="user-enabled" {{ $REQUEST->input('enabled') ? 'checked' : '' }}>
            <label for="user-enabled" class="form-check-label">{{ __('user-create.enabled') }}</label>
        </div>
    </div>
</div>
