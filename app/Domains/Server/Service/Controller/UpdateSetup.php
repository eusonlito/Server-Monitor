<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Server\Model\Server as Model;

class UpdateSetup extends ControllerAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     * @param \App\Domains\Server\Model\Server $row
     *
     * @return void
     */
    public function __construct(protected Request $request, protected Authenticatable $auth, protected Model $row)
    {
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'row' => $this->row,
            'setup' => $this->setup(),
        ];
    }
}
