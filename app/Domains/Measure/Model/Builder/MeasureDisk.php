<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;
use App\Domains\Measure\Model\Measure as Model;

class MeasureDisk extends BuilderAbstract
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
     * @param string $mount
     *
     * @return self
     */
    public function byMount(string $mount): self
    {
        return $this->where('mount', $mount);
    }

    /**
     * @param int $server_id
     *
     * @return self
     */
    public function byServerId(int $server_id): self
    {
        return $this->whereIn('measure_id', Model::query()->select('id')->byServerId($server_id));
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->orderBy('percent', 'DESC');
    }
}
