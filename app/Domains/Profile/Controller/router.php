<?php declare(strict_types=1);

namespace App\Domains\Profile\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth']], static function () {
    Route::any('/profile', Update::class)->name('profile.update');
});
