<?php declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Measure\Model\MeasureApp as MeasureAppModel;
use App\Domains\Measure\Model\MeasureDisk as MeasureDiskModel;
use App\Domains\Server\Model\Server as ServerModel;

class ServerCard extends Component
{
    /**
     * @var \App\Domains\Measure\Model\Measure
     */
    public ?MeasureModel $measure;

    /**
     * @var \App\Domains\Measure\Model\MeasureApp
     */
    public ?MeasureAppModel $appCpu;

    /**
     * @var \App\Domains\Measure\Model\MeasureApp
     */
    public ?MeasureAppModel $appMemory;

    /**
     * @var \App\Domains\Measure\Model\MeasureDisk
     */
    public ?MeasureDiskModel $disk;

    /**
     * @param \App\Domains\Server\Model\Server $server
     * @param bool $draggable = true
     *
     * @return void
     */
    public function __construct(public ServerModel $server, public bool $draggable = true)
    {
        $this->measure = $server->measure;
        $this->disk = $this->measure?->disk;
        $this->appCpu = $this->measure?->appCpu;
        $this->appMemory = $this->measure?->appMemory;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('domains.server.components.card');
    }
}
