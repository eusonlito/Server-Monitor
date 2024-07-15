<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Model\Server as Model;

class Create extends CreateUpdateAbstract
{
    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row = Model::query()->create([
            'name' => $this->data['name'],
            'ip' => $this->data['ip'],
            'auth' => $this->data['auth'],
            'enabled' => $this->data['enabled'],
            'dashboard' => $this->data['dashboard'],
        ]);
    }
}
