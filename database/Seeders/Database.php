<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Language\Seeder\Language as LanguageSeeder;

class Database extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $time = time();

        (new LanguageSeeder())->run();

        $this->command->info(sprintf('Seeding: Total Time %s seconds', time() - $time));
    }
}
