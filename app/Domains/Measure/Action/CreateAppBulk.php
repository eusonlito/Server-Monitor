<?php declare(strict_types=1);

namespace App\Domains\Measure\Action;

use App\Domains\Measure\Model\MeasureApp as MeasureAppModel;

class CreateAppBulk extends ActionAbstract
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
        MeasureAppModel::query()->insert($this->saveInsertData());
    }

    /**
     * @return array
     */
    protected function saveInsertData(): array
    {
        return array_map(fn ($app) => [
            'pid' => $app['pid'],
            'user' => $app['user'],
            'memory_virtual' => $app['memory_virtual'],
            'memory_resident' => $app['memory_resident'],
            'memory_percent' => $app['memory_percent'],
            'cpu_load' => $app['cpu_load'],
            'cpu_percent' => $app['cpu_percent'],
            'time' => $app['time'],
            'command' => $app['command'],
            'measure_id' => $this->row->id,
        ], $this->data['apps']);
    }

    /**
     * @return void
     */
    protected function saveRelate(): void
    {
        $this->saveRelateMeasureApp();
    }

    /**
     * @return void
     */
    protected function saveRelateMeasureApp(): void
    {
        $this->row->measure_app_cpu_id = $this->saveRelateMeasureAppCpuId();
        $this->row->measure_app_memory_id = $this->saveRelateMeasureAppMemoryId();
        $this->row->save();
    }

    /**
     * @return int
     */
    protected function saveRelateMeasureAppCpuId(): int
    {
        return MeasureAppModel::query()
            ->byMeasureId($this->row->id)
            ->orderBy('cpu_percent', 'DESC')
            ->limit(1)
            ->value('id');
    }

    /**
     * @return int
     */
    protected function saveRelateMeasureAppMemoryId(): int
    {
        return MeasureAppModel::query()
            ->byMeasureId($this->row->id)
            ->orderBy('memory_percent', 'DESC')
            ->limit(1)
            ->value('id');
    }
}
