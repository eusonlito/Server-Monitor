<?php declare(strict_types=1);

namespace App\Domains\Measure\Action;

use App\Domains\CoreApp\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\Measure\Model\Measure as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\Measure\Model\Measure
     */
    protected ?Model $row;
}
