<?php declare(strict_types=1);

namespace App\Domains\User\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\User\Model\User as Model;
use App\Domains\User\Model\Collection\User as Collection;

class Index extends ControllerAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     *
     * @return void
     */
    public function __construct(protected Request $request, protected Authenticatable $auth)
    {
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'list' => $this->list(),
        ];
    }

    /**
     * @return \App\Domains\User\Model\Collection\User
     */
    protected function list(): Collection
    {
        return Model::query()
            ->whenSearch($this->requestString('search'))
            ->whenOrder($this->requestString('order_column'), $this->requestString('order_mode'))
            ->list()
            ->get();
    }
}
