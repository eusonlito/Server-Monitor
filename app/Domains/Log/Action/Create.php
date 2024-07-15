<?php declare(strict_types=1);

namespace App\Domains\Log\Action;

use ReflectionClass;
use App\Domains\Log\Model\Log as Model;

class Create extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->data();
        $this->save();
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $main = current($this->data['related']);

        $this->data['action'] = $this->dataAction();
        $this->data['related_table'] = $main['related_table'];
        $this->data['related_id'] = $main['related_id'];
        $this->data['user_id'] = $this->auth->id ?? null;
    }

    /**
     * @return string
     */
    protected function dataAction(): string
    {
        return $this->data['action'] ?: (new ReflectionClass($this->data['class']))->getShortName();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveRow();
        $this->saveRelated();
    }

    /**
     * @return void
     */
    protected function saveRow(): void
    {
        $this->row = Model::query()->create([
            'action' => $this->data['action'],
            'ip' => $this->request->ip(),

            'payload' => $this->hidden($this->data['payload']),

            'related_table' => $this->data['related_table'],
            'related_id' => $this->data['related_id'],

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

            'user_id' => $this->data['user_id'],
        ]);
    }

    /**
     * @return void
     */
    protected function saveRelated(): void
    {
        foreach ($this->data['related'] as $each) {
            Model::query()->insert([
                'action' => $this->data['action'],
                'ip' => $this->request->ip(),

                'payload' => helper()->jsonEncode($this->hidden($each['payload'])),

                'related_table' => $each['related_table'],
                'related_id' => $each['related_id'],

                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),

                'log_id' => $this->row->id,
            ]);
        }
    }

    /**
     * @param array $input
     *
     * @return array
     */
    protected function hidden(array $input): array
    {
        return helper()->arrayMapRecursive($input, static function ($value, $key) {
            return ($key === 'password') ? 'HIDDEN' : $value;
        });
    }
}
