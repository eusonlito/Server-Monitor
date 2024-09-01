<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class() extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        if ($this->upMigrated()) {
            return;
        }

        $this->upTables();
        $this->upFinish();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('server', 'measure_retention');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('server', function (Blueprint $table) {
            $table->unsignedInteger('measure_retention')->default(0);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('server', function (Blueprint $table) {
            $table->dropColumn('measure_retention');
        });
    }
};
