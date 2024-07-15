<?php declare(strict_types=1);

namespace App\Domains\Measure\Action;

use App\Domains\Measure\Model\MeasureDisk as MeasureDiskModel;

class CreateDiskBulk extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->save();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveInsert();
        $this->saveRelate();
    }

    /**
     * @return void
     */
    protected function saveInsert(): void
    {
        MeasureDiskModel::query()->insert($this->saveInsertData());
    }

    /**
     * @return array
     */
    protected function saveInsertData(): array
    {
        return array_map(fn ($disk) => [
            'filesystem' => $disk['filesystem'],
            'size' => $disk['size'],
            'used' => $disk['used'],
            'available' => $disk['available'],
            'percent' => $disk['percent'],
            'mount' => $disk['mount'],
            'measure_id' => $this->row->id,
        ], $this->data['disks']);
    }

    /**
     * @return void
     */
    protected function saveRelate(): void
    {
        $this->saveRelateMeasureDisk();
    }

    /**
     * @return void
     */
    protected function saveRelateMeasureDisk(): void
    {
        $this->row->measure_disk_id = $this->saveRelateMeasureDiskId();
        $this->row->save();
    }

    /**
     * @return int
     */
    protected function saveRelateMeasureDiskId(): int
    {
        return MeasureDiskModel::query()
            ->byMeasureId($this->row->id)
            ->orderBy('percent', 'DESC')
            ->limit(1)
            ->value('id');
    }
}
