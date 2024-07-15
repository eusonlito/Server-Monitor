<?php declare(strict_types=1);

namespace App\Domains\UserCode\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class UserCode extends BuilderAbstract
{
    /**
     * @return self
     */
    public function available(): self
    {
        return $this->whereNull(['canceled_at', 'finished_at']);
    }

    /**
     * @param string $code
     *
     * @return self
     */
    public function byCode(string $code): self
    {
        return $this->where('code', $code);
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public function byIp(string $ip): self
    {
        return $this->where('ip', $ip);
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function byType(string $type): self
    {
        return $this->where('type', $type);
    }

    /**
     * @return self
     */
    public function whereNotExpired(): self
    {
        return $this->where(function ($q) {
            return $q->whereNull('expired_at')->orWhere('expired_at', '>=', date('Y-m-d H:i:s'));
        });
    }
}
