<?php declare(strict_types=1);

namespace App\Domains\SQLite\Schedule;

use App\Domains\Core\Schedule\ScheduleAbstract;

class Manager extends ScheduleAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->optimize();
        $this->vacuum();
    }

    /**
     * @return void
     */
    protected function optimize(): void
    {
        $this->command('sqlite:optimize', 'sqlite-optimize')->hourly();
    }

    /**
     * @return void
     */
    protected function vacuum(): void
    {
        $this->command('sqlite:vacuum', 'sqlite-vacuum')->daily();
    }
}
