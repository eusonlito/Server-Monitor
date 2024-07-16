<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class TopTasks extends ParserAbstract
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
            'total' => $this->integer($matches[1]),
            'running' => $this->integer($matches[2]),
            'sleeping' => $this->integer($matches[3]),
            'stopped' => $this->integer($matches[4]),
            'zombie' => $this->integer($matches[5]),
        ];
    }

    /**
     * @return string
     */
    protected function parseExp(): string
    {
        return '/Tasks: ([0-9]+) total,\s+([0-9]+) running,\s+([0-9]+) sleeping,\s+([0-9]+) stopped,\s+([0-9]+) zombie/';
    }
}
