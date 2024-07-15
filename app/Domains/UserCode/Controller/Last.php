<?php declare(strict_types=1);

namespace App\Domains\UserCode\Controller;

use Illuminate\Http\Response;
use App\Domains\UserCode\Model\UserCode as Model;

class Last extends ControllerAbstract
{
    /**
     * @param string $type
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $type): Response
    {
        return response($this->code($type));
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function code(string $type): string
    {
        return Model::query()
            ->byUserId($this->auth->id)
            ->byType($type)
            ->available()
            ->orderByLast()
            ->value('code') ?: $this->exceptionNotFound();
    }
}
