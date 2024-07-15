<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Exception\AuthFailed;
use App\Domains\User\Model\User as Model;

class Request extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->row();
        $this->check();
        $this->set();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = $this->request->user();
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        if (empty($this->row)) {
            throw new AuthFailed(__('user-auth.error.empty'));
        }
    }

    /**
     * @return void
     */
    protected function set(): void
    {
        $this->factory(row: $this->row)->action()->set();
    }
}
