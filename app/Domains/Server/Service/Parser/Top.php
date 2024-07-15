<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class Top extends ParserAbstract
{
    /**
     * @param string $top
     *
     * @return void
     */
    public function __construct(protected string $top)
    {
    }

    /**
     * @return array
     */
    public function apps(): array
    {
        return TopApps::new($this->top)->parse();
    }

    /**
     * @return array
     */
    public function cpuLoad(): array
    {
        return TopCpuLoad::new($this->top)->parse();
    }

    /**
     * @return array
     */
    public function tasks(): array
    {
        return TopTasks::new($this->top)->parse();
    }

    /**
     * @return array
     */
    public function memory(): array
    {
        return TopMemory::new($this->top)->parse();
    }

    /**
     * @return array
     */
    public function swap(): array
    {
        return TopSwap::new($this->top)->parse();
    }
}
