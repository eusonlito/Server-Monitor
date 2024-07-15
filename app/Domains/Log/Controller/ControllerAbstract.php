<?php declare(strict_types=1);

namespace App\Domains\Log\Controller;

use App\Domains\Core\Controller\ControllerWebAbstract;
use App\Domains\Log\Model\Log as Model;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Log\Model\Log
     */
    protected ?Model $row;

    /**
     * @param int $id
     *
     * @return \App\Domains\Log\Model\Log
     */
    protected function row(int $id): Model
    {
        return $this->row = Model::query()
            ->byId($id)
            ->withRelated()
            ->firstOr(fn () => $this->exceptionNotFound(__('log.error.not-found')));
    }
}
