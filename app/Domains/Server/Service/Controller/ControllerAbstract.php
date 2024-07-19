<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use App\Domains\CoreApp\Service\Controller\ControllerAbstract as ControllerAbstractCore;
use App\Domains\Measure\Model\Measure as MeasureModel;

abstract class ControllerAbstract extends ControllerAbstractCore
{
    /**
     * @return bool
     */
    protected function setup(): bool
    {
        return MeasureModel::query()
            ->byServerId($this->row->id)
            ->exists() === false;
    }
}
