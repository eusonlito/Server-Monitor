<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class View extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->blade();
    }

    /**
     * @return void
     */
    protected function blade(): void
    {
        Blade::directive('asset', function (string $expression) {
            return "<?= \App\Services\Html\Html::asset($expression); ?>";
        });

        Blade::directive('captcha', function (string $expression) {
            return "<?= \App\Services\Captcha\Captcha::new()->source($expression); ?>";
        });

        Blade::directive('cut', function (string $expression) {
            return "<?= \App\Services\Html\Html::cut($expression); ?>";
        });

        Blade::directive('dateLocal', function (string $expression) {
            return "<?= helper()->dateLocal($expression); ?>";
        });

        Blade::directive('dateUtcToLocal', function (string $expression) {
            return "<?= helper()->dateUtcToLocal($expression); ?>";
        });

        Blade::directive('icon', function (string $expression) {
            return "<?= \App\Services\Html\Html::icon($expression); ?>";
        });

        Blade::directive('image', function (string $expression) {
            return "<?= \App\Services\Html\Html::image($expression); ?>";
        });

        Blade::directive('inline', function (string $expression) {
            return "<?= \App\Services\Html\Html::inline($expression); ?>";
        });

        Blade::directive('number', function (string $expression) {
            return "<?= helper()->number($expression); ?>";
        });

        Blade::directive('progressbar', function (string $expression) {
            return "<?= \App\Services\Html\Html::progressbar($expression); ?>";
        });

        Blade::directive('query', function (string $expression) {
            return "<?= helper()->query($expression); ?>";
        });

        Blade::directive('secondsToTime', function (string $expression) {
            return "<?= helper()->secondsToTime($expression); ?>";
        });

        Blade::directive('sizeHuman', function (string $expression) {
            return "<?= helper()->sizeHuman($expression); ?>";
        });

        Blade::directive('status', function (string $expression) {
            return "<?= \App\Services\Html\Html::status($expression); ?>";
        });

        Blade::directive('svg', function (string $expression) {
            return "<?= \App\Services\Html\Html::svg($expression); ?>";
        });

        Blade::directive('thOrder', function (string $expression) {
            return "<?= \App\Services\Html\Html::thOrder($expression); ?>";
        });
    }
}
