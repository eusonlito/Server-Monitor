<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Measure\Model\MeasureApp as MeasureAppModel;
use App\Domains\Measure\Model\Collection\MeasureApp as MeasureAppCollection;
use App\Domains\Measure\Model\MeasureDisk as MeasureDiskModel;
use App\Domains\Measure\Model\Collection\MeasureDisk as MeasureDiskCollection;
use App\Domains\Server\Model\Server as Model;

class UpdateMeasureUpdate extends ControllerAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     * @param \App\Domains\Server\Model\Server $row
     * @param \App\Domains\Measure\Model\Measure $measure
     *
     * @return void
     */
    public function __construct(
        protected Request $request,
        protected Authenticatable $auth,
        protected Model $row,
        protected MeasureModel $measure
    ) {
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'row' => $this->row,
            'measure' => $this->measure,
            'apps' => $this->apps(),
            'disks' => $this->disks(),
        ];
    }

    /**
     * @return \App\Domains\Measure\Model\Collection\MeasureApp
     */
    public function apps(): MeasureAppCollection
    {
        return MeasureAppModel::query()
            ->byMeasureId($this->measure->id)
            ->list()
            ->get();
    }

    /**
     * @return \App\Domains\Measure\Model\Collection\MeasureDisk
     */
    public function disks(): MeasureDiskCollection
    {
        return MeasureDiskModel::query()
            ->byMeasureId($this->measure->id)
            ->list()
            ->get();
    }
}
