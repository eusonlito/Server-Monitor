<?php declare(strict_types=1);

namespace App\Domains\Log\Command;

use App\Domains\CoreApp\Command\CommandAbstract as CommandAbstractSahred;
use App\Domains\Log\Model\Log as Model;

abstract class CommandAbstract extends CommandAbstractSahred
{
    /**
     * @var \App\Domains\Log\Model\Log
     */
    protected Model $row;

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()->findOrFail($this->checkOption('id'));
    }
}
