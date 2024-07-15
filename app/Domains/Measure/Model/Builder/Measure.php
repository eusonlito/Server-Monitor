<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;

class Measure extends BuilderAbstract
{
    /**
     * @param int $server_id
     *
     * @return self
     */
    public function byServerId(int $server_id): self
    {
        return $this->where('server_id', $server_id);
    }

    /**
     * @return self
     */
    public function withAppCpu(): self
    {
        return $this->with('appCpu');
    }

    /**
     * @return self
     */
    public function withAppMemory(): self
    {
        return $this->with('appMemory');
    }

    /**
     * @return self
     */
    public function withDisk(): self
    {
        return $this->with('disk');
    }
}
