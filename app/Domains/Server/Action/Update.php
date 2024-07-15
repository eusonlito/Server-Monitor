<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

class Update extends CreateUpdateAbstract
{
    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row->name = $this->data['name'];
        $this->row->ip = $this->data['ip'];
        $this->row->auth = $this->data['auth'];
        $this->row->enabled = $this->data['enabled'];
        $this->row->dashboard = $this->data['dashboard'];
        $this->row->updated_at = date('Y-m-d H:i:s');

        $this->row->save();
    }
}
