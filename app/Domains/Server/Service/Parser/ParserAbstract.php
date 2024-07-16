<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

abstract class ParserAbstract
{
    /**
     * @var array
     */
    protected array $output;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param string $output
     * @param int $explode = PHP_INT_MAX
     *
     * @return array
     */
    public function cmdLinesArray(string $output, int $explode = PHP_INT_MAX): array
    {
        return $this->filter(array_map(
            fn ($line) => $this->toArray($line, $explode),
            $this->toLines($output)
        ));
    }

    /**
     * @param string $output
     * @param int $explode = PHP_INT_MAX
     *
     * @return array
     */
    protected function toArray(string $output, int $explode = PHP_INT_MAX): array
    {
        return $this->explode(' ', preg_replace('/\s+/', ' ', $output), $explode);
    }

    /**
     * @param string $output
     *
     * @return array
     */
    protected function toLines(string $output): array
    {
        return $this->explode("\n", $output);
    }

    /**
     * @param string $delimiter
     * @param string $output
     * @param int $max = PHP_INT_MAX
     *
     * @return array
     */
    protected function explode(string $delimiter, string $output, int $max = PHP_INT_MAX): array
    {
        return $this->filter(explode($delimiter, $output, $max));
    }

    /**
     * @param array $output
     *
     * @return array
     */
    protected function filter(array $output): array
    {
        return array_values(array_filter($output, static function ($value) {
            return is_string($value) ? strlen($value) : $value;
        }));
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function integer(mixed $value): int
    {
        return intval($value);
    }

    /**
     * @param mixed $value
     *
     * @return float
     */
    protected function float(mixed $value): float
    {
        return floatval(str_replace(',', '.', strval($value)));
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function size(mixed $value): int
    {
        $value = strval($value);

        if ($value === '0') {
            return 0;
        }

        if (is_numeric($value)) {
            return intval($value * 1024);
        }

        $unit = substr($value, -1);
        $value = floatval(substr(str_replace(',', '.', $value), 0, -1)) * 1024;

        return match ($unit) {
            'm' => intval($value * 1024),
            'g' => intval($value * 1024 * 1024),
            't' => intval($value * 1024 * 1024 * 1024),
            'p' => intval($value * 1024 * 1024 * 1024),
            default => intval($value),
        };
    }

    /**
     * @param string $value
     *
     * @return int
     */
    protected function time(string $value): int
    {
        return preg_match('/^([0-9]+):([0-9]{2})(?:\.([0-9]{2}))?$/', $value, $matches)
            ? (($matches[1] * 60) + $matches[2])
            : 0;
    }
}
