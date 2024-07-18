<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class Top extends TopAbstract
{
    /**
     * @return array
     */
    public function apps(): array
    {
        return TopApps::new($this->top, $this->cores)->parse();
    }

    /**
     * @return array
     */
    public function cpuLoad(): array
    {
        return TopCpuLoad::new($this->top, $this->cores)->parse();
    }

    /**
     * @return array
     */
    public function tasks(): array
    {
        return TopTasks::new($this->top, $this->cores)->parse();
    }

    /**
     * @return array
     */
    public function memory(): array
    {
        return TopMemory::new($this->top, $this->cores)->parse();
    }

    /**
     * @return array
     */
    public function swap(): array
    {
        return TopSwap::new($this->top, $this->cores)->parse();
    }
}
