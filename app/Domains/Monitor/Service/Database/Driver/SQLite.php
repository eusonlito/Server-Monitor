<?php declare(strict_types=1);

namespace App\Domains\Monitor\Service\Database\Driver;

use Illuminate\Support\Facades\DB;

class SQLite extends DriverAbstract
{
    /**
     * @return array
     */
    public function size(): array
    {
        if (isset($this->cache[__FUNCTION__])) {
            return $this->cache[__FUNCTION__];
        }

        return $this->cache[__FUNCTION__] = DB::select('
            SELECT
                `name` AS `table_name`,
                ROUND(SUM(`pgsize`) / 1024 / 1024, 2) AS `total_size`,
                ROUND(SUM(`pgsize`) / 1024 / 1024, 2) AS `table_size`,
                0 AS `index_size`
            FROM `dbstat`
            WHERE `table_name` IN (\''.implode("', '", $this->tables()).'\')
            GROUP BY `name`;
        ');
    }

    /**
     * @return array
     */
    public function count(): array
    {
        return $this->cache[__FUNCTION__] ??= (array)$this->db()->select($this->countSql())[0];
    }

    /**
     * @return string
     */
    protected function countSql(): string
    {
        $sql = [];

        foreach ($this->tables() as $table) {
            $sql[] = '(SELECT COUNT(*) FROM `'.$table.'`) AS `'.$table.'`';
        }

        return 'SELECT '.implode(', ', $sql).';';
    }
}
