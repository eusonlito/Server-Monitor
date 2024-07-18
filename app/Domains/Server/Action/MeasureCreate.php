<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Server\Service\Parser\Df as DfParser;
use App\Domains\Server\Service\Parser\Top as TopParser;
use App\Domains\Server\Service\Parser\Uptime as UptimeParser;
use App\Exceptions\ValidatorException;

class MeasureCreate extends ActionAbstract
{
    /**
     * @var \App\Domains\Measure\Model\Measure
     */
    protected MeasureModel $measure;

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->row();
        $this->data();
        $this->check();
        $this->dataMap();
        $this->save();
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = app('server');
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataIp();
        $this->dataTop();
        $this->dataDf();
        $this->dataUptime();
        $this->dataServerId();
    }

    /**
     * @return void
     */
    protected function dataIp(): void
    {
        $this->data['ip'] = $this->request->ip();
    }

    /**
     * @return void
     */
    protected function dataTop(): void
    {
        $parser = TopParser::new($this->data['top'], $this->data['cores']);

        $this->data['apps'] = $parser->apps();
        $this->data['cpu_load'] = $parser->cpuLoad();
        $this->data['tasks'] = $parser->tasks();
        $this->data['memory'] = $parser->memory();
        $this->data['swap'] = $parser->swap();
    }

    /**
     * @return void
     */
    protected function dataDf(): void
    {
        $this->data['disks'] = DfParser::new($this->data['df'])->parse();
    }

    /**
     * @return void
     */
    protected function dataUptime(): void
    {
        $this->data['uptime'] = UptimeParser::new($this->data['uptime'])->parse();
    }

    /**
     * @return void
     */
    protected function dataServerId(): void
    {
        $this->data['server_id'] = $this->row->id;
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        foreach ($this->checkList() as $key) {
            if (empty($this->data[$key])) {
                throw new ValidatorException($key);
            }
        }
    }

    /**
     * @return array
     */
    protected function checkList(): array
    {
        return ['apps', 'cpu_load', 'tasks', 'memory', 'swap', 'disks', 'uptime'];
    }

    /**
     * @return void
     */
    protected function dataMap(): void
    {
        $this->data['uptime'] = $this->data['uptime']['total'];
        $this->data['tasks_total'] = $this->data['tasks']['total'];
        $this->data['tasks_running'] = $this->data['tasks']['running'];
        $this->data['tasks_sleeping'] = $this->data['tasks']['sleeping'];
        $this->data['tasks_stopped'] = $this->data['tasks']['stopped'];
        $this->data['tasks_zombie'] = $this->data['tasks']['zombie'];
        $this->data['memory_total'] = intval(round($this->data['memory']['total'], 0));
        $this->data['memory_used'] = intval(round($this->data['memory']['used'], 0));
        $this->data['memory_free'] = intval(round($this->data['memory']['free'], 0));
        $this->data['memory_buffer'] = intval(round($this->data['memory']['buffer'], 0));
        $this->data['memory_available'] = intval(round($this->data['memory']['available'], 0));
        $this->data['memory_percent'] = intval(round($this->data['memory']['percent'], 0));
        $this->data['swap_total'] = intval(round($this->data['swap']['total'], 0));
        $this->data['swap_used'] = intval(round($this->data['swap']['used'], 0));
        $this->data['swap_free'] = intval(round($this->data['swap']['free'], 0));
        $this->data['swap_percent'] = intval(round($this->data['swap']['percent'], 0));
        $this->data['cpu_load_1'] = $this->data['cpu_load'][1];
        $this->data['cpu_load_5'] = $this->data['cpu_load'][5];
        $this->data['cpu_load_15'] = $this->data['cpu_load'][15];
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveMeasure();
        $this->saveMeasureApp();
        $this->saveMeasureDisk();
        $this->saveMeasureRelation();
    }

    /**
     * @return void
     */
    protected function saveMeasure(): void
    {
        $this->measure = $this->factory('Measure')
            ->action($this->saveMeasureData())
            ->create();
    }

    /**
     * @return array
     */
    protected function saveMeasureData(): array
    {
        return [
            'ip' => $this->data['ip'],
            'uptime' => $this->data['uptime'],
            'cores' => $this->data['cores'],
            'tasks_total' => $this->data['tasks_total'],
            'tasks_running' => $this->data['tasks_running'],
            'tasks_sleeping' => $this->data['tasks_sleeping'],
            'tasks_stopped' => $this->data['tasks_stopped'],
            'tasks_zombie' => $this->data['tasks_zombie'],
            'memory_total' => $this->data['memory_total'],
            'memory_used' => $this->data['memory_used'],
            'memory_free' => $this->data['memory_free'],
            'memory_buffer' => $this->data['memory_buffer'],
            'memory_available' => $this->data['memory_available'],
            'memory_percent' => $this->data['memory_percent'],
            'swap_total' => $this->data['swap_total'],
            'swap_used' => $this->data['swap_used'],
            'swap_free' => $this->data['swap_free'],
            'swap_percent' => $this->data['swap_percent'],
            'cpu_load_1' => $this->data['cpu_load_1'],
            'cpu_load_5' => $this->data['cpu_load_5'],
            'cpu_load_15' => $this->data['cpu_load_15'],
            'server_id' => $this->data['server_id'],
        ];
    }

    /**
     * @return void
     */
    protected function saveMeasureApp(): void
    {
        $this->factory('Measure', $this->measure)
            ->action($this->saveMeasureAppData())
            ->createAppBulk();
    }

    /**
     * @return array
     */
    protected function saveMeasureAppData(): array
    {
        return [
            'apps' => $this->data['apps'],
        ];
    }

    /**
     * @return void
     */
    protected function saveMeasureDisk(): void
    {
        $this->factory('Measure', $this->measure)
            ->action($this->saveMeasureDiskData())
            ->createDiskBulk();
    }

    /**
     * @return array
     */
    protected function saveMeasureDiskData(): array
    {
        return [
            'disks' => $this->data['disks'],
        ];
    }

    /**
     * @return void
     */
    protected function saveMeasureRelation(): void
    {
        $this->row->updated_at = date('Y-m-d H:i:s');
        $this->row->measure_id = $this->measure->id;
        $this->row->save();
    }
}
