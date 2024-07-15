<?php declare(strict_types=1);

namespace App\Domains\Profile\Action;

use Illuminate\Support\Facades\Hash;
use App\Domains\Language\Model\Language as LanguageModel;
use App\Domains\User\Model\User as Model;

class Update extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
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
        $this->dataName();
        $this->dataEmail();
        $this->dataPassword();
    }

    /**
     * @return void
     */
    protected function dataName(): void
    {
        $this->data['name'] = trim($this->data['name']);
    }

    /**
     * @return void
     */
    protected function dataEmail(): void
    {
        $this->data['email'] = strtolower($this->data['email']);
    }

    /**
     * @return void
     */
    protected function dataPassword(): void
    {
        if ($this->data['password']) {
            $this->data['password'] = Hash::make($this->data['password']);
        } else {
            $this->data['password'] = $this->row->password;
        }
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkEmail();
        $this->checkLanguageId();
    }

    /**
     * @return void
     */
    protected function checkEmail(): void
    {
        if ($this->checkEmailExists()) {
            $this->exceptionValidator(__('profile.error.email-exists'));
        }
    }

    /**
     * @return bool
     */
    protected function checkEmailExists(): bool
    {
        return Model::query()
            ->byIdNot($this->row->id)
            ->byEmail($this->data['email'])
            ->exists();
    }

    /**
     * @return void
     */
    protected function checkLanguageId(): void
    {
        if ($this->checkLanguageIdExists() === false) {
            $this->exceptionValidator(__('profile.error.language-exists'));
        }
    }

    /**
     * @return bool
     */
    protected function checkLanguageIdExists(): bool
    {
        return LanguageModel::query()
            ->byId($this->data['language_id'])
            ->exists();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row->name = $this->data['name'];
        $this->row->email = $this->data['email'];
        $this->row->password = $this->data['password'];
        $this->row->updated_at = date('Y-m-d H:i:s');
        $this->row->language_id = $this->data['language_id'];

        $this->row->save();
    }
}
