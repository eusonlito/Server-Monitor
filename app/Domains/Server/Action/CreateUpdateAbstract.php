<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Model\Server as Model;

abstract class CreateUpdateAbstract extends ActionAbstract
{
    /**
     * @return void
     */
    abstract protected function save(): void;

    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function handle(): Model
    {
        $this->check();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkName();
        $this->checkAuth();
    }

    /**
     * @return void
     */
    protected function checkName(): void
    {
        if ($this->checkNameExists()) {
            $this->exceptionValidator(__('server-create.error.name-exists'));
        }
    }

    /**
     * @return bool
     */
    protected function checkNameExists(): bool
    {
        return Model::query()
            ->byIdNot($this->row->id ?? 0)
            ->byName($this->data['name'])
            ->exists();
    }

    /**
     * @return void
     */
    protected function checkAuth(): void
    {
        if ($this->data['auth'] && $this->checkAuthExists()) {
            $this->exceptionValidator(__('server-create.error.auth-exists'));
        }
    }

    /**
     * @return bool
     */
    protected function checkAuthExists(): bool
    {
        return Model::query()
            ->byIdNot($this->row->id ?? 0)
            ->byAuth($this->data['auth'])
            ->exists();
    }
}
