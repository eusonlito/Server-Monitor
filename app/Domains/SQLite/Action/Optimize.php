<?php declare(strict_types=1);

namespace App\Domains\SQLite\Action;

class Optimize extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->db()->unprepared('PRAGMA optimize;');
    }
}
