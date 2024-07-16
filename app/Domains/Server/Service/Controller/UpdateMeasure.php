<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Server\Model\Server as Model;

class UpdateMeasure extends ControllerAbstract
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
            'measures' => $this->measures(),
        ];
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function measures(): LengthAwarePaginator
    {
        return MeasureModel::query()
            ->byServerId($this->row->id)
            ->byRequest($this->request)
            ->withAppCpu()
            ->withAppMemory()
            ->withDisk()
            ->list()
            ->paginate(20);
    }
}
