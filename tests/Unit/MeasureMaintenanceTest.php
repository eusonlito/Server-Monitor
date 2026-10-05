<?php declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;
use App\Domains\Measure\Model\Measure;
use App\Domains\Measure\Model\MeasureDisk;
use App\Domains\Server\Action\MeasureRetention;
use App\Domains\Server\Model\Server;
use App\Domains\Server\Schedule\Manager as ServerSchedule;
use App\Domains\Server\Service\Controller\UpdateChart;
use App\Domains\SQLite\Schedule\Manager as SQLiteSchedule;
use App\Domains\User\Model\User;
use Tests\CreatesApplication;

class MeasureMaintenanceTest extends TestCase
{
    use CreatesApplication;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        DB::unprepared('
            CREATE TABLE server (id INTEGER PRIMARY KEY, measure_retention INTEGER, enabled INTEGER DEFAULT 1,
                measure_id INTEGER REFERENCES measure(id) ON DELETE SET NULL);
            CREATE TABLE measure (id INTEGER PRIMARY KEY, server_id INTEGER REFERENCES server(id), created_at TEXT,
                cpu_percent REAL, memory_used INTEGER, memory_total INTEGER,
                measure_disk_id INTEGER REFERENCES measure_disk(id) ON DELETE SET NULL);
            CREATE TABLE measure_app (id INTEGER PRIMARY KEY, measure_id INTEGER REFERENCES measure(id) ON DELETE CASCADE);
            CREATE TABLE measure_disk (id INTEGER PRIMARY KEY, measure_id INTEGER REFERENCES measure(id) ON DELETE CASCADE,
                created_at TEXT, mount TEXT, used INTEGER, size INTEGER, available INTEGER, percent REAL);
            INSERT INTO server VALUES (1, 105, 1, NULL), (2, 1, 1, NULL);
        ');
    }

    /**
     * @param int $count
     *
     * @return void
     */
    protected function measures(int $count): void
    {
        DB::transaction(static function () use ($count) {
            for ($id = 1; $id <= $count; $id++) {
                $created_at = date('Y-m-d H:i:s', strtotime('2026-10-04 00:00:00') + $id * 60);

                DB::table('measure')->insert([
                    'id' => $id, 'server_id' => 1, 'created_at' => $created_at,
                    'cpu_percent' => ($id === 333) ? 100 : (($id === 334) ? -5 : 20),
                    'memory_used' => (($id === 777) ? 10 : 2) * 1073741824,
                    'memory_total' => 16 * 1073741824,
                ]);
                DB::table('measure_disk')->insert([
                    'id' => $id, 'measure_id' => $id, 'created_at' => $created_at,
                    'mount' => '/', 'used' => (($id === 555) ? 12 : 3) * 1073741824,
                    'size' => 16 * 1073741824, 'available' => 1073741824, 'percent' => 20,
                ]);
                DB::table('measure_app')->insert(['id' => $id, 'measure_id' => $id]);
                DB::table('measure')->where('id', $id)->update(['measure_disk_id' => $id]);
            }
        });

        DB::table('server')->where('id', 1)->update(['measure_id' => $count]);
    }

    /**
     * @return array
     */
    protected function chart(): array
    {
        $request = Request::create('/server/1/chart', 'GET', ['date_start' => '2026-10-04']);
        $this->app->instance('request', $request);

        return (new UpdateChart($request, new User(), Server::query()->findOrFail(1)))->data();
    }

    /**
     * @return void
     */
    public function test_long_charts_are_bounded_and_preserve_extremes_and_endpoints(): void
    {
        $this->measures(6000);
        $chart = $this->chart();
        $first = Measure::query()->findOrFail(1)->created_at;
        $last = Measure::query()->findOrFail(6000)->created_at;

        foreach (['cpu', 'memory', 'disk'] as $key) {
            $this->assertLessThanOrEqual(2000, count($chart[$key]));
            $this->assertSame($first, array_key_first($chart[$key]));
            $this->assertSame($last, array_key_last($chart[$key]));
        }

        $this->assertEquals(100, max($chart['cpu']));
        $this->assertEquals(-5, min($chart['cpu']));
        $this->assertEquals(10, max($chart['memory']));
        $this->assertEquals(12, max($chart['disk']));
    }

    /**
     * @return void
     */
    public function test_short_charts_keep_all_original_values(): void
    {
        $this->measures(20);
        $chart = $this->chart();

        $this->assertSame(Measure::query()->byServerId(1)->orderByFirst()->pluck('cpu_percent', 'created_at')->all(), $chart['cpu']);
        $this->assertCount(20, $chart['memory']);
        $this->assertCount(20, $chart['disk']);
    }

    /**
     * @return void
     */
    public function test_charts_keep_a_fixed_range_when_new_measurements_arrive(): void
    {
        $this->measures(20);
        $inserted = false;

        DB::listen(static function ($query) use (&$inserted) {
            if ($inserted || str_contains($query->sql, 'COUNT(*) AS count') === false) {
                return;
            }

            $inserted = true;
            DB::table('measure')->insert([
                'id' => 21, 'server_id' => 1, 'created_at' => '2026-10-05 00:00:00', 'cpu_percent' => 999,
            ]);
        });

        $chart = Measure::query()->byServerId(1)->chart('cpu_percent');

        $this->assertTrue($inserted);
        $this->assertCount(20, $chart);
        $this->assertEquals(20, max($chart));
    }

    /**
     * @return void
     */
    public function test_empty_charts_return_empty_series(): void
    {
        $chart = $this->chart();

        $this->assertSame([], $chart['cpu']);
        $this->assertSame([], $chart['memory']);
        $this->assertSame([], $chart['disk']);
    }

    /**
     * @return void
     */
    public function test_retention_commits_small_batches_and_keeps_the_configured_count(): void
    {
        $this->measures(705);
        DB::table('measure')->where('id', 1)->update(['server_id' => 2]);
        $remaining = 704;
        $batches = 0;

        DB::listen(function ($query) use (&$remaining, &$batches) {
            if (str_starts_with($query->sql, 'delete from "measure"') === false) {
                return;
            }

            $current = Measure::query()->byServerId(1)->count();
            $this->assertLessThanOrEqual(250, $remaining - $current);
            $this->assertSame(0, DB::connection()->transactionLevel());
            $remaining = $current;
            $batches++;
        });

        (new MeasureRetention(row: Server::query()->findOrFail(1)))->handle();

        $this->assertSame(3, $batches);
        $this->assertSame(105, Measure::query()->byServerId(1)->count());
        $this->assertSame(1, Measure::query()->byServerId(2)->count());
        $this->assertSame(106, DB::table('measure_app')->count());
        $this->assertSame(106, MeasureDisk::query()->count());
        $this->assertSame(705, Server::query()->findOrFail(1)->measure_id);
        $this->assertSame([], DB::select('PRAGMA foreign_key_check'));

        (new MeasureRetention(row: Server::query()->findOrFail(1)))->handle();
        $this->assertSame(3, $batches);
    }

    /**
     * @return void
     */
    public function test_zero_retention_does_not_delete_history(): void
    {
        $this->measures(20);
        $server = Server::query()->findOrFail(1);
        $server->measure_retention = 0;
        (new MeasureRetention(row: $server))->handle();

        $this->assertSame(20, Measure::query()->count());
    }

    /**
     * @return void
     */
    public function test_retention_releases_the_writer_lock_between_batches(): void
    {
        $this->measures(705);
        $path = tempnam(sys_get_temp_dir(), 'monitor-retention-');
        DB::statement('VACUUM INTO ?', [$path]);
        DB::purge('test');
        config(['database.connections.test.database' => $path]);
        $writer = new PDO('sqlite:'.$path);
        $writer->exec('PRAGMA busy_timeout = 50');
        $writes = 0;

        try {
            DB::listen(static function ($query) use ($writer, &$writes) {
                if (str_starts_with($query->sql, 'delete from "measure"') === false) {
                    return;
                }

                $writer->exec("INSERT INTO measure (server_id, created_at) VALUES (2, '2026-10-05 00:00:00')");
                $writes++;
            });

            (new MeasureRetention(row: Server::query()->findOrFail(1)))->handle();

            $this->assertSame(3, $writes);
            $this->assertSame(105, Measure::query()->byServerId(1)->count());
            $this->assertSame(3, Measure::query()->byServerId(2)->count());
        } finally {
            DB::purge('test');
            unset($writer);
            unlink($path);
        }
    }

    /**
     * @return void
     */
    public function test_vacuum_compacts_a_file_and_preserves_data(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'monitor-vacuum-');
        config(['database.connections.vacuum' => ['driver' => 'sqlite', 'database' => $path, 'prefix' => '']]);
        DB::setDefaultConnection('vacuum');

        try {
            DB::unprepared('CREATE TABLE sample (id INTEGER PRIMARY KEY, payload BLOB);
                WITH RECURSIVE ids(id) AS (SELECT 1 UNION ALL SELECT id+1 FROM ids WHERE id<100)
                INSERT INTO sample SELECT id, zeroblob(20000) FROM ids;
                DELETE FROM sample WHERE id<91;');
            clearstatcache(true, $path);
            $before = filesize($path);

            $this->assertSame(0, Artisan::call('sqlite:vacuum'));
            clearstatcache(true, $path);
            $this->assertLessThan($before, filesize($path));
            $this->assertSame(10, DB::table('sample')->count());
            $this->assertSame('ok', DB::selectOne('PRAGMA quick_check')->quick_check);
        } finally {
            DB::purge('vacuum');
            unlink($path);
        }
    }

    /**
     * @return void
     */
    public function test_schedules_avoid_daily_vacuum_and_overlapping_retention(): void
    {
        $schedule = new Schedule();
        (new SQLiteSchedule($schedule))->handle();
        (new ServerSchedule($schedule))->handle();
        $events = $schedule->events();

        $this->assertCount(2, $events);
        $this->assertStringContainsString('sqlite:optimize', $events[0]->command);
        $this->assertStringContainsString('server:measure:retention:all', $events[1]->command);
        $this->assertTrue($events[1]->withoutOverlapping);
        $this->assertTrue($events[1]->mutex->create($events[1]));
        $this->assertFalse($events[1]->mutex->create($events[1]));
        $events[1]->mutex->forget($events[1]);
    }
}
