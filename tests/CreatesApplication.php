<?php declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;

trait CreatesApplication
{
    /**
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->afterBootstrapping(LoadConfiguration::class, static function (Application $app) {
            $app['config']->set('database.connections.test', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
