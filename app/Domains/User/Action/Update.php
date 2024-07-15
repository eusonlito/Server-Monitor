<?php declare(strict_types=1);

namespace App\Domains\User\Action;

class Update extends CreateUpdateAbstract
{
    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row->name = $this->data['name'];
        $this->row->email = $this->data['email'];
        $this->row->password = $this->data['password'];

        $this->row->enabled = $this->data['enabled'];

        $this->row->updated_at = date('Y-m-d H:i:s');

        $this->row->language_id = $this->data['language_id'];

        $this->row->save();
    }
}
