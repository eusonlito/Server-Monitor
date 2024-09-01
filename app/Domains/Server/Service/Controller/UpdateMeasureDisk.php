<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Controller;

use stdClass;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Measure\Model\MeasureDisk as MeasureDiskModel;
use App\Domains\Server\Model\Server as Model;

class UpdateMeasureDisk extends ControllerAbstract
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
            'date_start' => date('Y-m-d', strtotime('-1 week')),
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
            'labels' => $this->labels(),
            'groups' => $this->groups(),
        ];
    }

    /**
     * @return array
     */
    protected function labels(): array
    {
        return array_values(array_unique(array_column($this->disks(), 'created_at')));
    }

    /**
     * @return array
     */
    protected function groups(): array
    {
        $groups = [];

        foreach ($this->disks() as $disk) {
            $groups[$disk->mount][$disk->created_at] = $this->group($disk);
        }

        return $groups;
    }

    /**
     * @param \stdClass $disk
     *
     * @return string
     */
    protected function group(stdClass $disk): string
    {
        return $disk->percent
            .' '.helper()->sizeHuman($disk->size)
            .' - '.helper()->sizeHuman($disk->used)
            .' = '.helper()->sizeHuman($disk->available);
    }

    /**
     * @return array
     */
    protected function disks(): array
    {
        static $cache;

        return $cache ??= MeasureDiskModel::query()
            ->select('used', 'size', 'available', 'mount', 'percent', 'created_at')
            ->byServerId($this->row->id)
            ->byRequest($this->request)
            ->orderByFirst()
            ->toBase()
            ->get()
            ->all();
    }
}
