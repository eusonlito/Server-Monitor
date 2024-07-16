<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Builder;

use Illuminate\Http\Request;
use App\Domains\CoreApp\Model\Builder\BuilderAbstract;

class Measure extends BuilderAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $exclude = ''
     *
     * @return self
     */
    public function byRequest(Request $request, string $exclude = ''): self
    {
        $value = $this->byRequestValue($request, 'date_start', 'date', $exclude);

        if ($value !== null) {
            $this->byCreatedAtAfter($value);
        }

        $value = $this->byRequestValue($request, 'date_end', 'date', $exclude);

        if ($value !== null) {
            $this->byCreatedAtBefore(date('Y-m-d', strtotime($value.' +1 day')));
        }

        return $this;
    }

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
