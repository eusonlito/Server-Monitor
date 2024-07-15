<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Exception\AuthFailed;
use App\Domains\Server\Model\Server as Model;

class Auth extends ActionAbstract
{
    /**
     * @return \App\Domains\Server\Model\Server
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->row();
        $this->checkIp();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->dataAuth();
    }

    /**
     * @return void
     */
    protected function dataAuth(): void
    {
        $this->data['auth'] = strval($this->request->header('Authorization'));
        $this->data['auth'] = preg_replace('/^\s*bearer\s+/i', '', $this->data['auth']);
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkAuth();
        $this->checkIpLock();
    }

    /**
     * @return void
     */
    protected function checkAuth(): void
    {
        if (helper()->uuidIsValid($this->data['auth']) === false) {
            $this->fail();
        }
    }

    /**
     * @return void
     */
    protected function checkIpLock(): void
    {
        $this->factory('IpLock')->action()->check();
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()
            ->byAuth($this->data['auth'])
            ->enabled()
            ->firstOr(fn () => $this->fail());
    }

    /**
     * @return void
     */
    protected function checkIp(): void
    {
        if (empty($this->row->ip)) {
            return;
        }

        $ips = array_map('trim', explode(',', $this->row->ip));

        if (in_array($this->request->ip(), $ips) === false) {
            $this->fail();
        }
    }

    /**
     * @throws \App\Domains\Server\Exception\AuthFailed
     *
     * @return void
     */
    protected function fail(): void
    {
        $this->factory('UserFail')->action($this->failData())->create();

        throw new AuthFailed(__('server-auth.error.auth-fail'));
    }

    /**
     * @return array
     */
    protected function failData(): array
    {
        return [
            'type' => 'server-auth',
            'text' => $this->data['auth'],
            'ip' => $this->request->ip(),
        ];
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        app()->bind('server', fn () => $this->row);
    }
}
