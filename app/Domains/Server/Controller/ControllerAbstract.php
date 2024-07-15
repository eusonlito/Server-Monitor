<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use App\Domains\CoreApp\Controller\ControllerWebAbstract;
use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Server\Model\Server as Model;

abstract class ControllerAbstract extends ControllerWebAbstract
{
    /**
     * @var ?\App\Domains\Server\Model\Server
     */
    protected ?Model $row;

    /**
     * @var ?\App\Domains\Measure\Model\Measure
     */
    protected ?MeasureModel $measure;

    /**
     * @param int $id
     *
     * @return \App\Domains\Server\Model\Server
     */
    protected function row(int $id): Model
    {
        return $this->row = Model::query()
            ->byId($id)
            ->firstOr(fn () => $this->exceptionNotFound(__('server.error.not-found')));
    }

    /**
     * @param int $measure_id
     *
     * @return \App\Domains\Measure\Model\Measure
     */
    protected function measure(int $measure_id): MeasureModel
    {
        return $this->measure = MeasureModel::query()
            ->byId($measure_id)
            ->byServerId($this->row->id)
            ->firstOr(fn () => $this->exceptionNotFound(__('server.error.measure-not-found')));
    }
}
