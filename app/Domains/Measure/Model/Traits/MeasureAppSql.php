<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Traits;

use Illuminate\Support\Facades\DB;

trait MeasureAppSql
{
    /**
     * @param int $server_id
     * @param array $filters = []
     *
     * @return array
     */
    public static function statsByServerId(int $server_id, array $filters = []): array
    {
        [$sql, $filters] = static::statsByServerIdFilters($filters);

        return DB::select($sql, array_filter([
            'server_id' => $server_id,
            'date_start' => $filters['date_start'],
            'date_end' => $filters['date_end'],
        ]));
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    public static function statsByServerIdFilters(array $filters): array
    {
        $sql = static::statsByServerIdSql();

        [$sql, $filters] = static::statsByServerIdFiltersDateStart($sql, $filters);
        [$sql, $filters] = static::statsByServerIdFiltersDateEnd($sql, $filters);

        return [$sql, $filters];
    }

    /**
     * @param string $sql
     * @param array $filters
     *
     * @return array
     */
    public static function statsByServerIdFiltersDateStart(string $sql, array $filters): array
    {
        $filters = static::statsByServerIdFiltersDateStartFilters($filters);

        if ($filters['date_start']) {
            $replacement = 'AND DATE("ma"."created_at") >= :date_start';
        } else {
            $replacement = '';
        }

        return [str_replace('AND_FILTER_BY_DATE_START', $replacement, $sql), $filters];
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    public static function statsByServerIdFiltersDateStartFilters(array $filters): array
    {
        $valid = is_string($filters['date_start'] ?? false)
            && preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $filters['date_start']);

        if ($valid === false) {
            $filters['date_start'] = null;
        }

        return $filters;
    }

    /**
     * @param string $sql
     * @param array $filters
     *
     * @return array
     */
    public static function statsByServerIdFiltersDateEnd(string $sql, array $filters): array
    {
        $filters = static::statsByServerIdFiltersDateEndFilters($filters);

        if ($filters['date_end']) {
            $replacement = 'AND DATE("ma"."created_at") >= :date_end';
        } else {
            $replacement = '';
        }

        return [str_replace('AND_FILTER_BY_DATE_END', $replacement, $sql), $filters];
    }

    /**
     * @param array $filters
     *
     * @return array
     */
    public static function statsByServerIdFiltersDateEndFilters(array $filters): array
    {
        $valid = is_string($filters['date_end'] ?? false)
            && preg_match('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/', $filters['date_end']);

        if ($valid === false) {
            $filters['date_end'] = null;
        }

        return $filters;
    }

    /**
     * @return string
     */
    public static function statsByServerIdSql(): string
    {
        return '
            WITH "stats" AS (
                SELECT
                    "ma"."command",
                    "ma"."user",

                    MAX("ma"."cpu_load") AS "cpu_load_max",
                    ROUND(AVG("ma"."cpu_load"), 1) AS "cpu_load_avg",
                    ROUND(MAX("ma"."cpu_percent"), 0) AS "cpu_percent_max",
                    ROUND(AVG("ma"."cpu_percent"), 0) AS "cpu_percent_avg",

                    MAX("ma"."memory_resident") AS "memory_resident_max",
                    AVG("ma"."memory_resident") AS "memory_resident_avg",
                    ROUND(MAX("ma"."memory_percent"), 0) AS "memory_percent_max",
                    ROUND(AVG("ma"."memory_percent"), 0) AS "memory_percent_avg",

                    MAX("ma"."time") AS "time"
                FROM "measure_app" "ma"
                JOIN "measure" "m" ON ("ma"."measure_id" = "m"."id")
                WHERE (
                    "m"."server_id" = :server_id
                    AND_FILTER_BY_DATE_START
                    AND_FILTER_BY_DATE_END
                )
                GROUP BY "ma"."command"
            ),
            "max_measure_ids" AS (
                SELECT
                    "ma"."command",
                    MAX(CASE WHEN "ma"."cpu_load" = "stats"."cpu_load_max" THEN "ma"."measure_id" ELSE NULL END) AS "cpu_percent_max_measure_id",
                    MAX(CASE WHEN "ma"."memory_resident" = "stats"."memory_resident_max" THEN "ma"."measure_id" ELSE NULL END) AS "memory_percent_max_measure_id"
                FROM "measure_app" "ma"
                JOIN "measure" "m" ON ("ma"."measure_id" = "m"."id")
                JOIN "stats" ON ("ma"."command" = "stats"."command")
                WHERE (
                    "m"."server_id" = :server_id
                    AND_FILTER_BY_DATE_START
                    AND_FILTER_BY_DATE_END
                )
                GROUP BY "ma"."command"
            )
            SELECT
                "s"."command",
                "s"."user",

                "s"."cpu_load_max",
                "s"."cpu_load_avg",
                "s"."cpu_percent_max",
                "s"."cpu_percent_avg",

                "s"."memory_resident_max",
                "s"."memory_resident_avg",
                "s"."memory_percent_max",
                "s"."memory_percent_avg",

                "s"."time",

                "mm"."cpu_percent_max_measure_id",
                "mm"."memory_percent_max_measure_id"
            FROM "stats" "s"
            JOIN "max_measure_ids" "mm" ON ("s"."command" = "mm"."command")
            ORDER BY "s"."memory_percent_max" DESC;
        ';
    }
}
