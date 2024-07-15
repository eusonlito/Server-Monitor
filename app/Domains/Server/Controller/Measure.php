<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

class Measure extends ControllerAbstract
{
    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->actionCall('measureCreate');
    }

    /**
     * @return void
     */
    protected function measureCreate(): void
    {
        $this->action()->measureCreate();
    }
}
