<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\Core\Action\ActionFactoryAbstract;
use App\Domains\User\Model\User as Model;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\User\Model\User
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authCredentials(): Model
    {
        return $this->actionHandle(AuthCredentials::class, $this->validate()->authCredentials());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function authModel(): Model
    {
        return $this->actionHandle(AuthModel::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function create(): Model
    {
        return $this->actionHandleTransaction(Create::class, $this->validate()->create());
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->actionHandle(Delete::class);
    }

    /**
     * @return void
     */
    public function logout(): void
    {
        $this->actionHandle(Logout::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function request(): Model
    {
        return $this->actionHandle(Request::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function set(): Model
    {
        return $this->actionHandle(Set::class);
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function update(): Model
    {
        return $this->actionHandleTransaction(Update::class, $this->validate()->update());
    }

    /**
     * @return \App\Domains\User\Model\User
     */
    public function updateBoolean(): Model
    {
        return $this->actionHandle(UpdateBoolean::class, $this->validate()->updateBoolean());
    }
}
