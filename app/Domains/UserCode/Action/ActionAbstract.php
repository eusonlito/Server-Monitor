<?php declare(strict_types=1);

namespace App\Domains\UserCode\Action;

use App\Domains\CoreApp\Action\ActionAbstract as ActionAbstractCore;
use App\Domains\User\Model\User as UserModel;
use App\Domains\UserCode\Model\UserCode as Model;

abstract class ActionAbstract extends ActionAbstractCore
{
    /**
     * @var ?\App\Domains\UserCode\Model\UserCode
     */
    protected ?Model $row;

    /**
     * @var \App\Domains\User\Model\User
     */
    protected UserModel $user;
}
