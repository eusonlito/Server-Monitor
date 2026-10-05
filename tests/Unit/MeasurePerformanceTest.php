<?php declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use App\Domains\Measure\Model\MeasureApp;
use Tests\CreatesApplication;

class MeasurePerformanceTest extends TestCase
{
    use CreatesApplication;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        DB::unprepared('
            CREATE TABLE measure (id INTEGER PRIMARY KEY, server_id INTEGER, created_at TEXT);
            CREATE TABLE measure_disk (id INTEGER PRIMARY KEY, measure_id INTEGER, created_at TEXT);
            CREATE TABLE measure_app (
                id INTEGER PRIMARY KEY, measure_id INTEGER, created_at TEXT,
                command TEXT, user TEXT, cpu_load REAL, cpu_percent REAL,
                memory_resident INTEGER, memory_percent REAL, time INTEGER
            );
        ');

        foreach ([
            [1, 1, '2026-10-03 23:59:59'],
            [2, 1, '2026-10-04 00:00:00'],
            [3, 1, '2026-10-04 23:59:59'],
            [4, 1, '2026-10-05 00:00:00'],
            [5, 2, '2026-10-04 12:00:00'],
        ] as [$id, $server_id, $created_at]) {
            DB::table('measure')->insert(compact('id', 'server_id', 'created_at'));
            DB::table('measure_app')->insert([
                'id' => $id, 'measure_id' => $id, 'created_at' => $created_at,
                'command' => 'sample', 'user' => 'sample',
                'cpu_load' => $id, 'cpu_percent' => $id,
                'memory_resident' => $id * 100, 'memory_percent' => $id,
                'time' => $id,
            ]);
        }
    }

    /**
     * @return void
     */
    public function test_stats_include_both_boundaries_and_exclude_next_day(): void
    {
        $stats = MeasureApp::statsByServerId(1, ['date_start' => '2026-10-04', 'date_end' => '2026-10-04']);

        $this->assertCount(1, $stats);
        $this->assertEquals(2.5, $stats[0]->cpu_load_avg);
        $this->assertEquals(3, $stats[0]->cpu_percent_max_measure_id);
        $this->assertEquals(3, $stats[0]->memory_percent_max_measure_id);
    }

    /**
     * @return void
     */
    public function test_stats_with_only_end_date_exclude_later_measurements(): void
    {
        $stats = MeasureApp::statsByServerId(1, ['date_end' => '2026-10-04']);

        $this->assertEquals(2, $stats[0]->cpu_load_avg);
        $this->assertEquals(3, $stats[0]->cpu_percent_max_measure_id);
    }

    /**
     * @return void
     */
    public function test_stats_without_dates_keep_all_server_measurements(): void
    {
        $stats = MeasureApp::statsByServerId(1);

        $this->assertEquals(2.5, $stats[0]->cpu_load_avg);
        $this->assertEquals(4, $stats[0]->cpu_percent_max_measure_id);
    }

    /**
     * @return void
     */
    public function test_date_filters_allow_index_range_searches(): void
    {
        [$sql, $filters] = MeasureApp::statsByServerIdFilters(['date_start' => '2026-10-04', 'date_end' => '2026-10-04']);

        $this->assertStringNotContainsString('DATE(', $sql);
        $this->assertSame('2026-10-05', $filters['date_end']);
    }

    /**
     * @return void
     */
    public function test_history_indexes_can_be_applied_and_reverted_twice(): void
    {
        $migration = require base_path('database/migrations/2026_10_05_150000_measure_history_index.php');
        $migration->up();
        $migration->up();

        $indexes = [
            'measure_server_id_created_at_index' => ['server_id', 'created_at'],
            'measure_app_measure_id_created_at_index' => ['measure_id', 'created_at'],
            'measure_disk_measure_id_created_at_index' => ['measure_id', 'created_at'],
        ];

        foreach ($indexes as $index => $columns) {
            $this->assertSame($columns, array_column(DB::select('PRAGMA index_info("'.$index.'")'), 'name'));
        }

        $plan = DB::select('EXPLAIN QUERY PLAN SELECT id FROM measure WHERE server_id = ? AND created_at > ?', [1, '2026-10-04']);
        $this->assertStringContainsString('server_id=? AND created_at>?', $plan[0]->detail);

        $migration->down();
        $migration->down();

        foreach ($indexes as $index => $columns) {
            $this->assertSame([], DB::select('PRAGMA index_info("'.$index.'")'));
        }
    }
}
