<?php declare(strict_types=1);

namespace App\Domains\SQLite\Command;

class Vacuum extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'sqlite:vacuum';

    /**
     * @var string
     */
    protected $description = 'Vacuum SQLite';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('START');

        $this->factory()->action()->vacuum();

        $this->info('END');
    }
}
