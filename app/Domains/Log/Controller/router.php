<?php declare(strict_types=1);

namespace App\Domains\Log\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth']], static function () {
    Route::get('/log', Index::class)->name('log.index');
    Route::get('/log/{id}', Detail::class)->name('log.detail');
    Route::get('/log/{code}/{id}', Related::class)->name('log.related');
});
