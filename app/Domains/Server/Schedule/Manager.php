<?php declare(strict_types=1);

namespace App\Domains\Server\Schedule;

use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->measureRetentionAll();
    }

    /**
     * @return void
     */
    protected function measureRetentionAll(): void
    {
        $this->command('server:measure:retention:all', 'server-measure-retention-all')->hourly();
    }
}
