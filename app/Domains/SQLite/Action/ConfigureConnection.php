<?php declare(strict_types=1);

namespace App\Domains\SQLite\Action;

class ConfigureConnection extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $db = $this->db();

        $db->unprepared('PRAGMA synchronous = NORMAL;');
        $db->unprepared('PRAGMA cache_size = -20000;');
        $db->unprepared('PRAGMA foreign_keys = ON;');
        $db->unprepared('PRAGMA temp_store = MEMORY;');
        $db->unprepared('PRAGMA busy_timeout = 5000;');
        $db->unprepared('PRAGMA mmap_size = 2147483648;');
        $db->unprepared('PRAGMA incremental_vacuum;');
    }
}
