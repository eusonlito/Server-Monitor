<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class Df extends ParserAbstract
{
    /**
     * @param string $df
     *
     * @return void
     */
    public function __construct(protected string $df)
    {
    }

    /**
     * @return array
     */
    public function parse(): array
    {
        return $this->sort($this->disks());
    }

    /**
     * @return array
     */
    protected function disks(): array
    {
        $disks = [];

        foreach ($this->cmdLinesArray($this->df, 6) as $line) {
            if ($line = $this->line($line)) {
                $disks[] = $line;
            }
        }

        return $disks;
    }

    /**
     * @param array $line
     *
     * @return ?array
     */
    protected function line(array $line): ?array
    {
        return $this->lineIsValid($line = $this->map($line)) ? $line : null;
    }

    /**
     * @param array $line
     *
     * @return array
     */
    protected function map(array $line): array
    {
        return [
            'filesystem' => ($line[0] ?? ''),
            'size' => $this->integer($line[1] ?? ''),
            'used' => $this->integer($line[2] ?? ''),
            'available' => $this->integer($line[3] ?? ''),
            'percent' => $this->integer($line[4] ?? ''),
            'mount' => ($line[5] ?? ''),
        ];
    }

    /**
     * @param array $line
     *
     * @return bool
     */
    protected function lineIsValid(array $line): bool
    {
        return $line['filesystem']
            && $line['size']
            && $line['used']
            && $line['mount']
            && (str_starts_with($line['filesystem'], '/dev/loop') === false);
    }

    /**
     * @param array $disks
     *
     * @return array
     */
    protected function sort(array $disks): array
    {
        usort($disks, static fn ($a, $b) => $b['percent'] <=> $a['percent']);

        return $disks;
    }
}
