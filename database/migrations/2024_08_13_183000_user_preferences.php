<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
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
        return Schema::hasColumn('user', 'preferences');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->jsonb('preferences')->nullable();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('preferences');
        });
    }
};
