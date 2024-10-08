<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['user-auth']], static function () {
    Route::any('/server', Index::class)->name('server.index');
    Route::any('/server/create', Create::class)->name('server.create');
    Route::post('/server/order', Order::class)->name('server.order');
    Route::any('/server/{id}', Update::class)->name('server.update');
    Route::any('/server/{id}/boolean/{column}', UpdateBoolean::class)->name('server.update.boolean');
    Route::any('/server/{id}/chart', UpdateChart::class)->name('server.update.chart');
    Route::any('/server/{id}/measure', UpdateMeasure::class)->name('server.update.measure');
    Route::any('/server/{id}/measure/{measure_id}', UpdateMeasureUpdate::class)->name('server.update.measure.update');
    Route::any('/server/{id}/measure-app', UpdateMeasureApp::class)->name('server.update.measure-app');
    Route::any('/server/{id}/measure-disk', UpdateMeasureDisk::class)->name('server.update.measure-disk');
    Route::any('/server/{id}/setup', UpdateSetup::class)->name('server.update.setup');
});

Route::group(['middleware' => ['server.auth']], static function () {
    Route::get('/server/script', Script::class)->name('server.script');
    Route::post('/server/measure', Measure::class)->name('server.measure');
});
