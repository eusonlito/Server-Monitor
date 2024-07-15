<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\CoreApp\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        $this->tables();
        $this->keys();
        $this->optimize();
    }

    /**
     * @return void
     */
    protected function tables(): void
    {
        Schema::create('ip_lock', function (Blueprint $table) {
            $table->id();

            $table->string('ip')->default('')->index();

            $table->dateTimeTz('end_at')->nullable();

            $this->timestamps($table);
        });

        Schema::create('language', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code')->unique();
            $table->string('iso')->unique();

            $table->boolean('rtl')->default(0);
            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(0);

            $this->timestamps($table);
        });

        Schema::create('log', function (Blueprint $table) {
            $table->id();

            $table->string('action')->index();
            $table->string('ip')->nullable()->index();

            $table->string('related_table')->index();
            $table->unsignedBigInteger('related_id')->nullable()->index()->nullable();

            $table->json('payload')->nullable();

            $this->dateTimeCreatedAt($table);

            $table->unsignedBigInteger('log_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('measure', function (Blueprint $table) {
            $table->id();

            $table->string('ip');

            $table->unsignedInteger('uptime');
            $table->unsignedTinyInteger('cores');

            $table->unsignedInteger('tasks_total');
            $table->unsignedInteger('tasks_running');
            $table->unsignedInteger('tasks_sleeping');
            $table->unsignedInteger('tasks_stopped');
            $table->unsignedInteger('tasks_zombie');

            $table->unsignedBigInteger('memory_total');
            $table->unsignedBigInteger('memory_used')->index();
            $table->unsignedBigInteger('memory_free');
            $table->unsignedBigInteger('memory_buffer');
            $table->unsignedBigInteger('memory_available');
            $table->unsignedTinyInteger('memory_percent');

            $table->unsignedBigInteger('swap_total');
            $table->unsignedBigInteger('swap_used');
            $table->unsignedBigInteger('swap_free');
            $table->unsignedTinyInteger('swap_percent');

            $table->float('cpu_load_1');
            $table->float('cpu_load_5');
            $table->float('cpu_load_15');

            $table->float('cpu_percent')->index();

            $this->dateTimeCreatedAt($table);

            $table->unsignedBigInteger('measure_app_cpu_id')->nullable();
            $table->unsignedBigInteger('measure_app_memory_id')->nullable();
            $table->unsignedBigInteger('measure_disk_id')->nullable();
            $table->unsignedBigInteger('server_id');
        });

        Schema::create('measure_app', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('pid');
            $table->string('user');
            $table->unsignedBigInteger('memory_virtual');
            $table->unsignedBigInteger('memory_resident')->index();
            $table->float('memory_percent');
            $table->float('cpu_load');
            $table->float('cpu_percent')->index();
            $table->unsignedInteger('time');
            $table->string('command');

            $this->dateTimeCreatedAt($table);

            $table->unsignedBigInteger('measure_id');
        });

        Schema::create('measure_disk', function (Blueprint $table) {
            $table->id();

            $table->string('filesystem');
            $table->unsignedBigInteger('size');
            $table->unsignedBigInteger('used');
            $table->unsignedBigInteger('available');
            $table->unsignedTinyInteger('percent')->index();
            $table->string('mount');

            $this->dateTimeCreatedAt($table);

            $table->unsignedBigInteger('measure_id');
        });

        Schema::create('queue_fail', function (Blueprint $table) {
            $table->id();

            $table->text('connection');
            $table->text('queue');

            $table->longText('payload');
            $table->longText('exception');

            $table->dateTime('failed_at')->useCurrent();

            $this->timestamps($table);
        });

        Schema::create('server', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();
            $table->string('ip')->default('');
            $table->string('auth')->unique();

            $table->unsignedInteger('order')->default(0);

            $table->boolean('enabled')->default(0);
            $table->boolean('dashboard')->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('measure_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('user', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default('');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();

            $table->boolean('enabled')->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('language_id');
        });

        Schema::create('user_code', function (Blueprint $table) {
            $table->id();

            $table->string('type')->index();
            $table->string('code')->index();
            $table->string('ip');

            $table->dateTimeTz('expired_at')->nullable();
            $table->dateTimeTz('finished_at')->nullable();
            $table->dateTimeTz('canceled_at')->nullable();

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id');
        });

        Schema::create('user_fail', function (Blueprint $table) {
            $table->id();

            $table->string('type')->index();
            $table->string('text')->nullable();
            $table->string('ip')->index();

            $this->dateTimeCreatedAt($table);

            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('user_session', function (Blueprint $table) {
            $table->id();

            $table->string('auth')->index();
            $table->string('ip')->index();

            $this->dateTimeCreatedAt($table);

            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    /**
     * @return void
     */
    protected function keys(): void
    {
        Schema::table('log', function (Blueprint $table) {
            $table->index(['related_table', 'related_id']);
            $table->index(['related_table', 'related_id', 'action']);

            $this->foreignOnDeleteCascade($table, 'log');
            $this->foreignOnDeleteSetNull($table, 'user');
        });

        Schema::table('measure', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'measure_app', 'measure_app_cpu_id');
            $this->foreignOnDeleteSetNull($table, 'measure_app', 'measure_app_memory_id');
            $this->foreignOnDeleteSetNull($table, 'measure_disk', 'measure_disk_id');
            $this->foreignOnDeleteCascade($table, 'server');
        });

        Schema::table('measure_app', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'measure');
        });

        Schema::table('measure_disk', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'measure');
        });

        Schema::table('server', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'measure');
            $this->foreignOnDeleteSetNull($table, 'user');
        });

        Schema::table('user', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'language');
        });

        Schema::table('user_code', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('user_fail', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'user');
        });

        Schema::table('user_session', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'user');
        });
    }

    /**
     * @return void
     */
    protected function optimize(): void
    {
        $this->db()->unprepared('PRAGMA journal_mode = wal;');
        $this->db()->unprepared('PRAGMA synchronous = normal;');
        $this->db()->unprepared('PRAGMA foreign_keys = on;');
        $this->db()->unprepared('PRAGMA temp_store = memory;');
        $this->db()->unprepared('PRAGMA mmap_size = 30000000000;');
        $this->db()->unprepared('PRAGMA page_size = 32768;');
        $this->db()->unprepared('PRAGMA auto_vacuum = incremental;');
        $this->db()->unprepared('PRAGMA incremental_vacuum;');
    }
};
