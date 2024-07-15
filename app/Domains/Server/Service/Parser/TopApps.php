<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class TopApps extends ParserAbstract
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
        return $this->limit($this->lines());
    }

    /**
     * @return array
     */
    protected function lines(): array
    {
        $lines = [];

        foreach ($this->cmdLinesArray($this->top, 13) as $line) {
            $line = $this->line($line);

            if (empty($line)) {
                continue;
            }

            $index = $line['command'];

            if (empty($lines[$index])) {
                $lines[$index] = $line;
            }

            $lines[$index]['cpu_percent'] += $line['cpu_load'];
            $lines[$index]['count']++;
        }

        foreach ($lines as $index => $app) {
            $lines[$index]['cpu_percent'] = round($app['cpu_percent'] / $app['count'], 2);
        }

        return $lines;
    }

    /**
     * @param array $line
     *
     * @return ?array
     */
    protected function line(array $line): ?array
    {
        return $this->lineIsValid($line = $this->map($line))
            ? $line
            : null;
    }

    /**
     * @param array $line
     *
     * @return array
     */
    protected function map(array $line): array
    {
        return [
            'pid' => $this->integer($line[0] ?? ''),
            'user' => ($line[1] ?? ''),
            'priority' => $this->integer($line[2] ?? ''),
            'nice' => $this->integer($line[3] ?? ''),
            'memory_virtual' => $this->size($line[4] ?? ''),
            'memory_resident' => $this->size($line[5] ?? ''),
            'shared' => $this->size($line[6] ?? ''),
            'status' => ($line[7] ?? ''),
            'cpu_load' => $this->float($line[8] ?? ''),
            'memory_percent' => $this->float($line[9] ?? ''),
            'time' => $this->time($line[10] ?? ''),
            'command' => ($line[11] ?? ''),
            'cpu_percent' => 0,
            'count' => 0,
        ];
    }

    /**
     * @param array $line
     *
     * @return bool
     */
    protected function lineIsValid(array $line): bool
    {
        return $line['pid']
            && $line['user']
            && $line['memory_virtual']
            && $line['memory_resident']
            && ($line['memory_percent'] || $line['cpu_percent'])
            && ($line['command'] !== 'top');
    }

    /**
     * @param array $apps
     *
     * @return array
     */
    protected function limit(array $apps): array
    {
        uasort($apps, static fn ($a, $b) => $b['cpu_percent'] <=> $a['cpu_percent']);

        $cpu = array_slice($apps, 0, 20);

        uasort($apps, static fn ($a, $b) => $b['memory_percent'] <=> $a['memory_percent']);

        $memory = array_slice($apps, 0, 20);

        return $cpu + $memory;
    }
}
