<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Model\Server as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Server\Model\Server
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function auth(): Model
    {
        return $this->actionHandle(Auth::class);
    }

    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function create(): Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create());
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
    public function measureCreate(): void
    {
        $this->actionHandle(MeasureCreate::class, $this->validate()->measureCreate());
    }

    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function measureRetention(): Model
    {
        return $this->actionHandle(MeasureRetention::class);
    }

    /**
     * @return void
     */
    public function measureRetentionAll(): void
    {
        $this->actionHandle(MeasureRetentionAll::class);
    }

    /**
     * @return void
     */
    public function order(): void
    {
        $this->actionHandle(Order::class, $this->validate()->order());
    }

    /**
     * @return string
     */
    public function script(): string
    {
        return $this->actionHandle(Script::class);
    }

    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function update(): Model
    {
        return $this->actionHandle(Update::class, $this->validate()->update());
    }

    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function updateBoolean(): Model
    {
        return $this->actionHandle(UpdateBoolean::class, $this->validate()->updateBoolean());
    }
}
