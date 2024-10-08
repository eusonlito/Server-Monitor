<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class TopSwap extends TopAbstract
{
    /**
     * @return array
     */
    public function parse(): array
    {
        if (preg_match($this->parseExp(), $this->top, $matches) === 0) {
            return [];
        }

        $mult = match ($matches[1]) {
            'K' => 1024,
            'M' => 1024 * 1024,
            'G' => 1024 * 1024 * 1024,
        };

        $total = $this->float($matches[2]) * $mult;

        if ($matches[4] === 'free') {
            $free = $this->float($matches[3]) * $mult;
            $used = $this->float($matches[5]) * $mult;
        } else {
            $free = $this->float($matches[5]) * $mult;
            $used = $this->float($matches[3]) * $mult;
        }

        $available = $total - $used;
        $percent = $total ? intval(round($used / $total * 100)) : 0;

        return [
            'total' => $total,
            'free' => $free,
            'used' => $used,
            'available' => $available,
            'percent' => $percent,
        ];
    }

    /**
     * @return string
     */
    protected function parseExp(): string
    {
        return '/(K|M|G)iB Swap\s*:\s*([0-9]+(?:[,.][0-9]+)?)\s*total,\s*([0-9]+(?:[,.][0-9]+)?)\s*(free|used),\s*([0-9]+(?:[,.][0-9]+)?)\s*(free|used)/';
    }
}
