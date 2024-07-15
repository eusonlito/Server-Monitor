<?php declare(strict_types=1);

namespace App\Domains\Server\Middleware;

use App\Domains\Core\Middleware\MiddlewareAbstract as MiddlewareAbstractCore;
use App\Domains\Server\Model\Server as Model;

abstract class MiddlewareAbstract extends MiddlewareAbstractCore
{
    /**
     * @var ?\App\Domains\Server\Model\Server
     */
    protected ?Model $row;
}
