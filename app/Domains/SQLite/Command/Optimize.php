<?php declare(strict_types=1);

namespace App\Domains\SQLite\Command;

class Optimize extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'sqlite:optimize';

    /**
     * @var string
     */
    protected $description = 'Optimize SQLite';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('START');

        $this->factory()->action()->optimize();

        $this->info('END');
    }
}
