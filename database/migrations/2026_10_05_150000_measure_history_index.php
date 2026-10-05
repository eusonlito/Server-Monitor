<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\CoreApp\Migration\MigrationAbstract;

return new class() extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::table('measure', function (Blueprint $table) {
            $this->tableAddIndex($table, ['server_id', 'created_at']);
        });

        Schema::table('measure_app', function (Blueprint $table) {
            $this->tableAddIndex($table, ['measure_id', 'created_at']);
        });

        Schema::table('measure_disk', function (Blueprint $table) {
            $this->tableAddIndex($table, ['measure_id', 'created_at']);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('measure', function (Blueprint $table) {
            $this->tableDropIndex($table, ['server_id', 'created_at']);
        });

        Schema::table('measure_app', function (Blueprint $table) {
            $this->tableDropIndex($table, ['measure_id', 'created_at']);
        });

        Schema::table('measure_disk', function (Blueprint $table) {
            $this->tableDropIndex($table, ['measure_id', 'created_at']);
        });
    }
};
