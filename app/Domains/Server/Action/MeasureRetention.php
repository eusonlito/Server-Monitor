<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Model\Server as Model;
use App\Domains\Measure\Model\Measure as MeasureModel;

class MeasureRetention extends ActionAbstract
{
    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function handle(): Model
    {
        if ($this->isAvailable() === false) {
            return $this->row;
        }

        $this->data();
        $this->delete();

        return $this->row;
    }

    /**
     * @return bool
     */
    protected function isAvailable(): bool
    {
        return boolval($this->row->measure_retention);
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataMeasureId();
    }

    /**
     * @return void
     */
    protected function dataMeasureId(): void
    {
        $this->data['measure_id'] = $this->dataMeasureIdValue();
    }

    /**
     * @return ?int
     */
    protected function dataMeasureIdValue(): ?int
    {
        return MeasureModel::query()
            ->byServerId($this->row->id)
            ->orderByLast()
            ->offset($this->row->measure_retention - 1)
            ->value('id');
    }

    /**
     * @return void
     */
    protected function delete(): void
    {
        if (empty($this->data['measure_id'])) {
            return;
        }

        MeasureModel::query()
            ->byServerId($this->row->id)
            ->byIdBefore($this->data['measure_id'])
            ->delete();
    }
}
