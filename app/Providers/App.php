<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Domains\Core\Traits\Factory;

class App extends ServiceProvider
{
    use Factory;

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->https();
        $this->locale();
    }

    /**
     * @return void
     */
    public function https(): void
    {
        if (config('app.https') === false) {
            return;
        }

        URL::forceScheme('https');

        $this->app['request']->server->set('HTTPS', 'on');
    }

    /**
     * @return void
     */
    protected function locale(): void
    {
        $locale = config('app.locale_system')[config('app.locale')];

        setlocale(LC_COLLATE, $locale);
        setlocale(LC_CTYPE, $locale);
        setlocale(LC_MONETARY, $locale);
        setlocale(LC_TIME, $locale);

        if (defined('LC_MESSAGES')) {
            setlocale(LC_MESSAGES, $locale);
        }
    }
}
