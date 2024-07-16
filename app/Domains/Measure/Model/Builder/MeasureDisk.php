<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Builder;

use Illuminate\Http\Request;
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
     * @param \Illuminate\Http\Request $request
     * @param string $exclude = ''
     *
     * @return self
     */
    public function byRequest(Request $request, string $exclude = ''): self
    {
        $value = $this->byRequestValue($request, 'date_start', 'date', $exclude);

        if ($value !== null) {
            $this->byCreatedAtAfter($value);
        }

        $value = $this->byRequestValue($request, 'date_end', 'date', $exclude);

        if ($value !== null) {
            $this->byCreatedAtBefore(date('Y-m-d', strtotime($value.' +1 day')));
        }

        return $this;
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
