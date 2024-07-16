<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class TopCpuLoad extends ParserAbstract
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
    public function parse(): array
    {
        if (preg_match($this->parseExp(), $this->top, $matches) === 0) {
            return [];
        }

        return [
            1 => $this->float($matches[1]),
            5 => $this->float($matches[2]),
            15 => $this->float($matches[3]),
        ];
    }

    /**
     * @return string
     */
    protected function parseExp(): string
    {
        return '/load average: ([0-9]+[,\.][0-9]+),\s*([0-9]+[,\.][0-9]+),\s*([0-9]+[,\.][0-9]+)/';
    }
}
