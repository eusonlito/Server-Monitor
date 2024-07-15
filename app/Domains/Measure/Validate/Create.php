<?php declare(strict_types=1);

namespace App\Domains\Measure\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'ip' => ['bail', 'ip', 'required'],
            'uptime' => ['bail', 'integer', 'required'],
            'cores' => ['bail', 'integer', 'required'],
            'tasks_total' => ['bail', 'integer'],
            'tasks_running' => ['bail', 'integer'],
            'tasks_sleeping' => ['bail', 'integer'],
            'tasks_stopped' => ['bail', 'integer'],
            'tasks_zombie' => ['bail', 'integer'],
            'memory_total' => ['bail', 'integer'],
            'memory_used' => ['bail', 'integer'],
            'memory_free' => ['bail', 'integer'],
            'memory_buffer' => ['bail', 'integer'],
            'memory_available' => ['bail', 'integer'],
            'memory_percent' => ['bail', 'integer'],
            'swap_total' => ['bail', 'integer'],
            'swap_used' => ['bail', 'integer'],
            'swap_free' => ['bail', 'integer'],
            'swap_percent' => ['bail', 'integer'],
            'cpu_load_1' => ['bail', 'numeric'],
            'cpu_load_5' => ['bail', 'numeric'],
            'cpu_load_15' => ['bail', 'numeric'],
            'server_id' => ['bail', 'integer', 'required'],
        ];
    }
}
