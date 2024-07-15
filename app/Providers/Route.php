<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route as RouteFacade;

class Route extends RouteServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->patterns();

        parent::boot();
    }

    /**
     * @return void
     */
    protected function patterns(): void
    {
        RouteFacade::pattern('id', '[0-9]+');
    }

    /**
     * @return void
     */
    public function map(): void
    {
        $this->mapWeb();
    }

    /**
     * @return void
     */
    protected function mapWeb(): void
    {
        $this->mapLoad('web', 'Controller');
    }

    /**
     * @param array|string $middleware
     * @param string $path
     *
     * @return void
     */
    protected function mapLoad(array|string $middleware, string $path): void
    {
        Route::middleware($middleware)
            ->group(fn () => $this->mapLoadRouter($path));
    }

    /**
     * @param string $path
     *
     * @return void
     */
    protected function mapLoadRouter(string $path): void
    {
        foreach (glob(app_path('Domains/*/'.$path.'/router.php')) as $file) {
            require $file;
        }
    }
}
