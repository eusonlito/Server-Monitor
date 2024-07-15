<?php declare(strict_types=1);

namespace App\Domains\UserCode\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth-enabled']], static function () {
    Route::get('/user-code/last/{type}', Last::class)->name('user-code.last');
});
