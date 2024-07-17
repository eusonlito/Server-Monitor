<?php declare(strict_types=1);

namespace App\Domains\Server\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;

class Server extends BuilderAbstract
{
    /**
     * @param string $auth
     *
     * @return self
     */
    public function byAuth(string $auth): self
    {
        return $this->where('auth', $auth);
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function byName(string $name): self
    {
        return $this->where('name', $name);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this
            ->orderBy('enabled', 'DESC')
            ->orderBy('dashboard', 'DESC')
            ->orderByRaw('`order` = 0 ASC')
            ->orderBy('order', 'ASC');
    }

    /**
     * @return self
     */
    public function whenMeasureRetention(): self
    {
        return $this->where('measure_retention', '>', '0');
    }

    /**
     * @param bool $dashboard = true
     *
     * @return self
     */
    public function whereDashboard(bool $dashboard = true): self
    {
        return $this->where('dashboard', $dashboard);
    }

    /**
     * @return self
     */
    public function withMeasure(): self
    {
        return $this->with(['measure' => static fn ($q) => $q->withAppCpu()->withAppMemory()->withDisk()->list()]);
    }
}
