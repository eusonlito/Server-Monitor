<?php declare(strict_types=1);

namespace App\Domains\SQLite\Action;

class ConfigureGlobal extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->db()->unprepared('PRAGMA journal_mode = WAL;');
        $this->db()->unprepared('PRAGMA synchronous = NORMAL;');
        $this->db()->unprepared('PRAGMA page_size = 32768;');
        $this->db()->unprepared('PRAGMA cache_size = -20000;');
        $this->db()->unprepared('PRAGMA auto_vacuum = incremental;');
    }
}
