<?php declare(strict_types=1);

namespace App\Domains\UserCode\Action;

use App\Domains\UserCode\Model\UserCode as Model;

class Check extends ActionAbstract
{
    /**
     * @return \App\Domains\UserCode\Model\UserCode
     */
    public function handle(): Model
    {
        $this->data();
        $this->row();
        $this->check();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataCode();
    }

    /**
     * @return void
     */
    protected function dataCode(): void
    {
        $this->data['code'] = strtoupper($this->data['code']);
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()
            ->byType($this->data['type'])
            ->byUserId($this->data['user_id'])
            ->orderByLast()
            ->first();
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkRow();
        $this->checkCode();
        $this->checkExpired();
    }

    /**
     * @return void
     */
    protected function checkRow(): void
    {
        if (empty($this->row)) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function checkCode(): void
    {
        if ($this->data['code'] !== $this->row->code) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function checkExpired(): void
    {
        if ($this->row->finished_at || $this->row->canceled_at) {
            $this->fail();
        }

        if ($this->row->expired_at <= date('Y-m-d H:i:s')) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function fail(): void
    {
        $this->factory('UserFail')->action($this->failData())->create();

        $this->exceptionValidator(__('user-code-check.validate.invalid'));
    }

    /**
     * @return array
     */
    protected function failData(): array
    {
        return [
            'type' => $this->data['type'],
            'text' => $this->data['code'],
            'ip' => $this->request->ip(),
            'user_id' => $this->data['user_id'],
        ];
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        if ($this->data['finish'] === false) {
            return;
        }

        $this->row->finished_at = date('Y-m-d H:i:s');
        $this->row->updated_at = date('Y-m-d H:i:s');
        $this->row->save();
    }
}
