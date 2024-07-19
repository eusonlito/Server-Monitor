<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Measure\Model\MeasureDisk as MeasureDiskModel;
use App\Domains\Measure\Model\Collection\MeasureDisk as MeasureDiskCollection;
use App\Domains\Server\Model\Server as Model;

class UpdateChart extends ControllerAbstract
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
            'cpu' => $this->cpu(),
            'memory' => $this->memory(),
            'memory_max' => $this->memoryMax(),
            'disk' => $this->disk(),
            'disk_max' => $this->diskMax(),
            'disk_name' => $this->diskName(),
            'disks' => $this->disks(),
        ];
    }

    /**
     * @return array
     */
    protected function cpu(): array
    {
        return MeasureModel::query()
            ->byServerId($this->row->id)
            ->byRequest($this->request)
            ->orderByFirst()
            ->pluck('cpu_percent', 'created_at')
            ->all();
    }

    /**
     * @return array
     */
    protected function memory(): array
    {
        return array_map(static fn ($value) => round($value / 1024 / 1024 / 1024, 2), $this->memoryValues());
    }

    /**
     * @return array
     */
    protected function memoryValues(): array
    {
        return MeasureModel::query()
            ->byServerId($this->row->id)
            ->byRequest($this->request)
            ->orderByFirst()
            ->pluck('memory_used', 'created_at')
            ->all();
    }

    /**
     * @return float
     */
    protected function memoryMax(): float
    {
        return round($this->memoryMaxValue() / 1024 / 1024 / 1024, 2);
    }

    /**
     * @return int
     */
    protected function memoryMaxValue(): int
    {
        return MeasureModel::query()
            ->byServerId($this->row->id)
            ->byRequest($this->request)
            ->max('memory_total') ?: 0;
    }

    /**
     * @return array
     */
    protected function disk(): array
    {
        return array_map(static fn ($value) => round($value / 1024 / 1024 / 1024, 2), $this->diskValues());
    }

    /**
     * @return array
     */
    protected function diskValues(): array
    {
        if (empty($this->row->measure->disk)) {
            return [];
        }

        return MeasureDiskModel::query()
            ->byServerId($this->row->id)
            ->byMount($this->row->measure->disk->mount)
            ->byRequest($this->request)
            ->orderByFirst()
            ->pluck('used', 'created_at')
            ->all();
    }

    /**
     * @return float
     */
    protected function diskMax(): float
    {
        if (empty($this->row->measure->disk)) {
            return 0;
        }

        return round(($this->row->measure->disk->size ?: 0) / 1024 / 1024 / 1024, 2);
    }

    /**
     * @return string
     */
    protected function diskName(): string
    {
        if (empty($this->row->measure->disk)) {
            return '-';
        }

        return $this->row->measure->disk->mount;
    }

    /**
     * @return ?\App\Domains\Measure\Model\Collection\MeasureDisk
     */
    protected function disks(): ?MeasureDiskCollection
    {
        if (empty($this->row->measure)) {
            return null;
        }

        return $this->row->measure?->disks;
    }
}
