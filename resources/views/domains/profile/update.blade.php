@extends ('domains.profile.update-layout')

@section ('content')

<form method="post">
    <input type="hidden" name="_action" value="update" />

    <div class="box p-5 mt-5">
        <div class="p-2">
            <label for="profile-update-name" class="form-label">{{ __('profile-update.name') }}</label>
            <input type="text" name="name" class="form-control form-control-lg" id="profile-update-name" value="{{ $REQUEST->input('name') }}" required>
        </div>

        <div class="p-2">
            <label for="profile-update-email" class="form-label">{{ __('profile-update.email') }}</label>
            <input type="email" name="email" class="form-control form-control-lg" id="profile-update-email" value="{{ $REQUEST->input('email') }}" required>
        </div>

        <div class="p-2">
            <label for="profile-update-password" class="form-label">{{ __('profile-update.password') }}</label>

            <div class="input-group">
                <input type="password" name="password" class="form-control form-control-lg" id="profile-update-password">
                <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.show') }}" data-password-show="#profile-update-password" tabindex="-1">@icon('eye', 'w-5 h-5')</button>
            </div>
        </div>

        <div class="p-2">
            <x-select name="language_id" :options="$language_options" :label="__('profile-update.language')" required></x-select>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('profile-update.save') }}</button>
        </div>
    </div>
</form>

@stop
