<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;

class MeasureApp extends BuilderAbstract
{
    /**
     * @param int $measure_id
     *
     * @return self
     */
    public function byMeasureId(int $measure_id): self
    {
        return $this->where('measure_id', $measure_id);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->select($this->addTable([
            'id',
            'command',
            'cpu_load',
            'cpu_percent',
            'command',
            'memory_resident',
            'memory_percent',
            'measure_id',
        ]));
    }

    /**
     * @return self
     */
    public function orderByMemoryDesc(): self
    {
        return $this->orderBy('memory_percent', 'DESC');
    }
}
