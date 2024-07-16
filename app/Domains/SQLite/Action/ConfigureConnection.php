<?php declare(strict_types=1);

namespace App\Domains\SQLite\Action;

class ConfigureConnection extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->db()->unprepared('PRAGMA synchronous = NORMAL;');
        $this->db()->unprepared('PRAGMA foreign_keys = ON;');
        $this->db()->unprepared('PRAGMA temp_store = memory;');
        $this->db()->unprepared('PRAGMA busy_timeout = 5000;');
        $this->db()->unprepared('PRAGMA mmap_size = 30000000000;');
        $this->db()->unprepared('PRAGMA cache_size = -20000;');
        $this->db()->unprepared('PRAGMA incremental_vacuum;');
    }
}
