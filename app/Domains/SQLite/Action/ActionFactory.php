<?php declare(strict_types=1);

namespace App\Domains\SQLite\Action;

use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @return void
     */
    public function configureConnection(): void
    {
        $this->actionHandle(ConfigureConnection::class);
    }

    /**
     * @return void
     */
    public function configureGlobal(): void
    {
        $this->actionHandle(ConfigureGlobal::class);
    }

    /**
     * @return void
     */
    public function optimize(): void
    {
        $this->actionHandle(Optimize::class);
    }

    /**
     * @return void
     */
    public function vacuum(): void
    {
        $this->actionHandle(Vacuum::class);
    }
}
