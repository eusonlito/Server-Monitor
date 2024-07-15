<?php declare(strict_types=1);

namespace App\Domains\Measure\Action;

use App\Domains\Measure\Model\Measure as Model;
use App\Domains\Server\Model\Server as ServerModel;

class Create extends ActionAbstract
{
    /**
     * @return \App\Domains\Measure\Model\Measure
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataCpuPercent();
    }

    /**
     * @return void
     */
    protected function dataCpuPercent(): void
    {
        $this->data['cpu_percent'] = $this->data['cpu_load_1'] / $this->data['cores'];
        $this->data['cpu_percent'] = intval(round($this->data['cpu_percent'] * 100));
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkServerId();
    }

    /**
     * @return void
     */
    protected function checkServerId(): void
    {
        if ($this->checkServerIdExists() === false) {
            $this->exceptionValidator(__('measure-create.error.server_id-exists'));
        }
    }

    /**
     * @return bool
     */
    protected function checkServerIdExists(): bool
    {
        return ServerModel::query()
            ->byId($this->data['server_id'])
            ->exists();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row = Model::query()->create([
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
            'cpu_percent' => $this->data['cpu_percent'],
            'server_id' => $this->data['server_id'],
        ]);
    }
}
