<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Measure\Model\MeasureApp as MeasureAppModel;
use App\Domains\Server\Model\Server as Model;

class UpdateMeasureApp extends ControllerAbstract
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
        $this->request();
    }

    /**
     * @return void
     */
    protected function request(): void
    {
        $this->requestMerge([
            'date_start' => date('Y-m-d', strtotime('-1 day')),
        ]);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'row' => $this->row,
            'setup' => $this->setup(),
            'apps' => $this->apps(),
        ];
    }

    /**
     * @return array
     */
    public function apps(): array
    {
        return MeasureAppModel::statsByServerId($this->row->id, $this->request->input());
    }
}
