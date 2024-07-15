<?php declare(strict_types=1);

namespace App\Domains\Profile\Controller;

use App\Domains\CoreApp\Controller\ControllerWebAbstract;
use App\Domains\User\Model\User as Model;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = $this->auth;
    }
}
