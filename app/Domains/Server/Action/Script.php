<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

class Script extends ActionAbstract
{
    /**
     * @const string
     */
    protected const string FILE = 'resources/app/server/script';

    /**
     * @return string
     */
    public function handle(): string
    {
        return $this->script();
    }

    /**
     * @return string
     */
    protected function script(): string
    {
        return strtr($this->contents(), [
            ':TOKEN' => app('server')->auth,
            ':URL' => route('server.measure'),
        ]);
    }

    /**
     * @return string
     */
    protected function contents(): string
    {
        return file_get_contents(base_path(static::FILE));
    }
}
