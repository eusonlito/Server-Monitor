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
    }

    /**
     * @return void
     */
    protected function tables(): void
    {
        Schema::table('log', function (Blueprint $table) {
            $this->tableAddIndex($table, 'log_id');
            $this->tableAddIndex($table, 'user_id');
        });

        Schema::table('measure', function (Blueprint $table) {
            $this->tableAddIndex($table, 'memory_used');
            $this->tableAddIndex($table, 'memory_percent');
            $this->tableAddIndex($table, 'cpu_percent');
            $this->tableAddIndex($table, 'measure_app_cpu_id');
            $this->tableAddIndex($table, 'measure_app_memory_id');
            $this->tableAddIndex($table, 'measure_disk_id');
            $this->tableAddIndex($table, 'server_id');
        });

        Schema::table('measure_app', function (Blueprint $table) {
            $this->tableAddIndex($table, 'memory_resident');
            $this->tableAddIndex($table, 'cpu_percent');
            $this->tableAddIndex($table, 'measure_id');
        });

        Schema::table('measure_disk', function (Blueprint $table) {
            $this->tableAddIndex($table, 'percent');
            $this->tableAddIndex($table, 'measure_id');
        });

        Schema::table('server', function (Blueprint $table) {
            $this->tableAddIndex($table, 'measure_id');
            $this->tableAddIndex($table, 'user_id');
        });

        Schema::table('user', function (Blueprint $table) {
            $this->tableAddIndex($table, 'language_id');
        });

        Schema::table('user_code', function (Blueprint $table) {
            $this->tableAddIndex($table, 'user_id');
        });

        Schema::table('user_fail', function (Blueprint $table) {
            $this->tableAddIndex($table, 'user_id');
        });

        Schema::table('user_session', function (Blueprint $table) {
            $this->tableAddIndex($table, 'user_id');
        });
    }
};
