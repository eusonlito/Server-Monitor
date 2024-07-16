<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Builder;

use Illuminate\Http\Request;
use App\Domains\CoreApp\Model\Builder\BuilderAbstract;
use App\Domains\Measure\Model\MeasureDisk as MeasureDiskModel;

class Measure extends BuilderAbstract
{
    /**
     * @var array
     */
    protected array $simpleOrder = [
        'memory_percent', 'cpu_percent', 'created_at',
    ];

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
        return $this->where('server_id', $server_id);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->select($this->addTable([
            'id',
            'memory_used',
            'memory_total',
            'memory_percent',
            'cpu_load_1',
            'cpu_load_5',
            'cpu_load_15',
            'cores',
            'cpu_percent',
            'created_at',
            'measure_app_cpu_id',
            'measure_app_memory_id',
            'measure_disk_id',
            'server_id',
        ]));
    }

    /**
     * @param ?string $mode
     *
     * @return self
     */
    public function orderByMeasureDisk(?string $mode): self
    {
        return $this->orderByLeftJoin(MeasureDiskModel::class, 'percent', $mode);
    }

    /**
     * @param ?string $column, ?string $mode
     *
     * @return self
     */
    public function whenOrder(?string $column, ?string $mode): self
    {
        return match ($column) {
            'measure_disk' => $this->orderByMeasureDisk($mode),
            default => $this->simpleOrder($column, $mode),
        };
    }

    /**
     * @return self
     */
    public function withAppCpu(): self
    {
        return $this->with(['appCpu' => static fn ($q) => $q->list()]);
    }

    /**
     * @return self
     */
    public function withAppMemory(): self
    {
        return $this->with(['appMemory' => static fn ($q) => $q->list()]);
    }

    /**
     * @return self
     */
    public function withDisk(): self
    {
        return $this->with(['disk' => static fn ($q) => $q->list()]);
    }
}
