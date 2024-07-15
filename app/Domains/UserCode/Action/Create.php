<?php declare(strict_types=1);

namespace App\Domains\UserCode\Action;

use App\Domains\User\Model\User as UserModel;
use App\Domains\UserCode\Model\UserCode as Model;

class Create extends ActionAbstract
{
    /**
     * @return \App\Domains\UserCode\Model\UserCode
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
        $this->dataCode();
        $this->dataIp();
    }

    /**
     * @return void
     */
    protected function dataCode(): void
    {
        $this->data['code'] = helper()->uniqidReal(rand(6, 8), true, 'upper');
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
    protected function check(): void
    {
        $this->checkUserId();
    }

    /**
     * @return void
     */
    protected function checkUserId(): void
    {
        if ($this->checkUserIdExists() === false) {
            $this->exceptionValidator(__('user-code.error.user_id-exists'));
        }
    }

    /**
     * @return bool
     */
    protected function checkUserIdExists(): bool
    {
        return UserModel::query()
            ->byId($this->data['user_id'])
            ->exists();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->savePrevious();
        $this->saveRow();
    }

    /**
     * @return void
     */
    protected function savePrevious(): void
    {
        Model::query()
            ->byUserId($this->data['user_id'])
            ->available()
            ->update(['canceled_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * @return void
     */
    protected function saveRow(): void
    {
        $this->row = Model::query()->create([
            'type' => $this->data['type'],
            'code' => $this->data['code'],
            'ip' => $this->data['ip'],

            'expired_at' => $this->data['expired_at'],

            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),

            'user_id' => $this->data['user_id'],
        ])->fresh();
    }
}
