<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class Uptime extends ParserAbstract
{
    /**
     * @param string $uptime
     *
     * @return void
     */
    public function __construct(protected string $uptime)
    {
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        if (preg_match($this->parseExp(), $this->uptime, $matches) === 0) {
            return [];
        }

        return [
            'total' => intval(round(floatval($matches[1]), 0)),
            'idle' => intval(round(floatval($matches[2]), 0)),
        ];
    }

    /**
     * @return string
     */
    protected function parseExp(): string
    {
        return '/^([0-9]+\.[0-9]+) ([0-9]+\.[0-9]+)$/';
    }
}
