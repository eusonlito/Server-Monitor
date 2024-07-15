<?php declare(strict_types=1);

namespace App\Domains\User\Command;

class Create extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'user:create {--email=} {--name=} {--password=} {--enabled}';

    /**
     * @var string
     */
    protected $description = 'Create User with {--email=} {--name=} {--password=} {--enabled}';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('[START]');

        $this->checkOptions(['email', 'name', 'password']);
        $this->requestWithOptions();

        $this->info($this->factory()->action()->create()->only('id', 'email', 'name', 'enabled'));

        $this->info('[END]');
    }
}
