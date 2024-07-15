<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;
use App\Domains\CoreApp\Action\UpdateBoolean as UpdateBooleanCoreApp;

class UpdateBoolean extends UpdateBooleanCoreApp
{
    /**
     * @var \App\Domains\User\Model\User
     */
    protected Model $row;
}
