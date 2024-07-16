<?php declare(strict_types=1);

namespace App\Domains\SQLite\Command;

class ConfigureGlobal extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'sqlite:configure:global';

    /**
     * @var string
     */
    protected $description = 'Configure Global SQLite Pragma';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('START');

        $this->factory()->action()->configureGlobal();

        $this->info('END');
    }
}
