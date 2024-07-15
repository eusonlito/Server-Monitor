<?php declare(strict_types=1);

namespace App\Domains\UserCode\Action;

use App\Domains\UserCode\Model\UserCode as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\UserCode\Model\UserCode
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\UserCode\Model\UserCode
     */
    public function check(): Model
    {
        return $this->actionHandle(Check::class, $this->validate()->check());
    }

    /**
     * @return \App\Domains\UserCode\Model\UserCode
     */
    public function create(): Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create());
    }
}
