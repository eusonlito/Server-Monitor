<?php declare(strict_types=1);

namespace App\Domains\Measure\Action;

use App\Domains\Measure\Model\Measure as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Measure\Model\Measure
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\Measure\Model\Measure
     */
    public function create(): Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create());
    }

    /**
     * @return void
     */
    public function createDiskBulk(): void
    {
        $this->actionHandle(CreateDiskBulk::class, $this->validate()->createDiskBulk());
    }

    /**
     * @return void
     */
    public function createAppBulk(): void
    {
        $this->actionHandle(CreateAppBulk::class, $this->validate()->createAppBulk());
    }
}
