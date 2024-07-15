<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use Illuminate\Http\Response;

class Script extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        return response($this->actionCall('script'));
    }

    /**
     * @return string
     */
    protected function script(): string
    {
        return $this->action()->script();
    }
}
