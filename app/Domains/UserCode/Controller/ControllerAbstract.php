<?php declare(strict_types=1);

namespace App\Domains\UserCode\Controller;

use App\Domains\Core\Controller\ControllerAbstract as ControllerAbstractCore;
use App\Domains\UserCode\Model\UserCode as Model;

abstract class ControllerAbstract extends ControllerAbstractCore
{
    /**
     * @var ?\App\Domains\UserCode\Model\UserCode
     */
    protected ?Model $row;

    /**
     * @param int $id
     *
     * @return \App\Domains\UserCode\Model\UserCode
     */
    protected function row(int $id): Model
    {
        return $this->row = Model::query()
            ->byId($id)
            ->firstOr(fn () => $this->exceptionNotFound(__('user-code.error.not-found')));
    }
}
