<?php declare(strict_types=1);

namespace App\Domains\Server\Command;

class MeasureRetentionAll extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'server:measure:retention:all';

    /**
     * @var string
     */
    protected $description = 'Check All Servers Measures retention';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('START');

        $this->factory()->action()->measureRetentionAll();

        $this->info('END');
    }
}
