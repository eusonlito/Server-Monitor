<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

class Order extends ControllerAbstract
{
    /**
     * @return void
     */
    public function __invoke(): void
    {
        $this->actionCall('order');
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        $this->action()->order();
    }
}
