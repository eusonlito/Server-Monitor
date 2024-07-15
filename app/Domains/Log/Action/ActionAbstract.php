<?php declare(strict_types=1);

namespace App\Domains\Log\Action;

use App\Domains\Log\Model\Log as Model;
use App\Domains\CoreApp\Action\ActionAbstract as ActionAbstractCore;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Log\Model\Log
     */
    protected ?Model $row;
}
