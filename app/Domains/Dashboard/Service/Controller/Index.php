<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Server\Model\Collection\Server as ServerCollection;
use App\Domains\Server\Model\Server as ServerModel;

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
            'servers' => $this->servers(),
        ];
    }

    /**
     * @return \App\Domains\Server\Model\Collection\Server
     */
    protected function servers(): ServerCollection
    {
        return ServerModel::query()
            ->enabled()
            ->whereDashboard()
            ->withMeasure()
            ->list()
            ->get();
    }
}
