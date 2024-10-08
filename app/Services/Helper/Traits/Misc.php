<?php declare(strict_types=1);

namespace App\Services\Helper\Traits;

trait Misc
{
    /**
     * @return string
     */
    public function uuid(): string
    {
        $data = random_bytes(16);

        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @param mixed $uuid
     *
     * @return bool
     */
    public function uuidIsValid(mixed $uuid): bool
    {
        return is_string($uuid) && preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid);
    }

    /**
     * @return string
     */
    public function objectId(): string
    {
        static $machineId;
        static $processId;

        $machineId ??= substr(md5(gethostname()), 0, 6);
        $processId ??= dechex(getmypid() & 0xFFFF);

        $timestamp = dechex(time());
        $counter = str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

        return $timestamp.$machineId.$processId.$counter;
    }

    /**
     * @param int $length
     * @param bool $safe = false
     * @param ?string $case = null
     *
     * @return string
     */
    public function uniqidReal(int $length, bool $safe = false, ?string $case = null): string
    {
        if ($safe) {
            $string = '23456789bcdfghjkmnpqrstwxyzBCDFGHJKMNPQRSTWXYZ';
        } else {
            $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        $string = match ($case) {
            'lower' => strtolower($string),
            'upper' => strtoupper($string),
            default => $string,
        };

        return substr(str_shuffle(str_repeat($string, rand((int)($length / 2), $length))), 0, $length);
    }

    /**
     * @param mixed $value
     *
     * @return ?string
     */
    public function jsonEncode($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    /**
     * @param array $query
     *
     * @return string
     */
    public function query(array $query): string
    {
        return http_build_query($query + request()->query());
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function simpleName(string $string): string
    {
        return ucwords($this->simpleString($string), ' ');
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function simpleString(string $string): string
    {
        return str_slug($string, ' ');
    }

    /**
     * @param string $string
     * @param float $a = 1
     *
     * @return string
     */
    public function stringToRGBA(string $string, float $a = 1): string
    {
        $hash = md5($string);

        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        return 'rgba('.$r.', '.$g.', '.$b.', '.$a.')';
    }
}
