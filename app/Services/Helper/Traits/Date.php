<?php declare(strict_types=1);

namespace App\Services\Helper\Traits;

use DateTime;
use DateTimeZone;
use IntlDateFormatter;
use Throwable;

trait Date
{
    /**
     * @param int $seconds
     *
     * @return string
     */
    public function timeHuman(int $seconds): string
    {
        return sprintf('%02d:%02d:%02d', floor($seconds / 3600), fmod(floor($seconds / 60), 60), floor(fmod($seconds, 60)));
    }

    /**
     * @param int $seconds
     * @param string $default = '-'
     *
     * @return string
     */
    public function timeDateHuman(int $seconds, string $default = '-'): string
    {
        $s = $seconds % 60;
        $m = floor(($seconds % 3600) / 60);
        $h = floor(($seconds % 86400) / 3600);
        $d = floor(($seconds % 2592000) / 86400);

        $human = [];

        if ($d === 1) {
            $human[] = '1 '.__('common.day');
        } elseif ($d) {
            $human[] = $d.' '.__('common.days');
        }

        if ($h === 1) {
            $human[] = '1 '.__('common.hour');
        } elseif ($h) {
            $human[] = $h.' '.__('common.hours');
        }

        if ($m === 1) {
            $human[] = '1 '.__('common.minute');
        } elseif ($m) {
            $human[] = $m.' '.__('common.minutes');
        }

        if ($s === 1) {
            $human[] = '1 '.__('common.second');
        } elseif ($s) {
            $human[] = $s.' '.__('common.seconds');
        }

        if (empty($human)) {
            return $default;
        }

        $last = array_pop($human);

        if (empty($human)) {
            return strtolower($last);
        }

        return strtolower(implode(', ', $human).' '.__('common.and').' '.$last);
    }

    /**
     * @param int|string|null $date
     * @param bool $hour = true
     * @param bool $second = false
     * @param ?string $default = '-'
     *
     * @return ?string
     */
    public function dateLocal(int|string|null $date, bool $hour = true, bool $second = false, ?string $default = '-'): ?string
    {
        if (empty($date)) {
            return $default;
        }

        if (is_int($date)) {
            $time = $date;
            $date = date('Y-m-d H:i:s', $time);
        } else {
            $time = strtotime($date);
        }

        if ($time === false) {
            return $default;
        }

        $format = 'd/m/Y';

        if (str_contains($date, ' ')) {
            $format .= $hour ? ' H:i' : '';
            $format .= ($hour && $second) ? ':s' : '';
        }

        return date($format, $time);
    }

    /**
     * @param int|string|null $date
     * @param bool $hour = true
     * @param bool $second = false
     * @param ?string $default = '-'
     *
     * @return ?string
     */
    public function dateUtcToLocal(int|string|null $date, bool $hour = true, bool $second = false, ?string $default = '-'): ?string
    {
        if (empty($date)) {
            return $default;
        }

        if (is_string($date)) {
            $date = strtotime($date);
        }

        return $this->dateLocal($date + 7200, $hour, $second, $default);
    }

    /**
     * @param ?string $date
     * @param ?string $default = null
     *
     * @return ?string
     */
    public function dateToDate(?string $date, ?string $default = null): ?string
    {
        if (empty($date)) {
            return $default;
        }

        [$day, $time] = explode(' ', $date) + ['', ''];

        if (str_contains($day, ':')) {
            [$day, $time] = [$time, $day];
        }

        if (!preg_match('#^[0-9]{1,4}[/\-][0-9]{1,2}[/\-][0-9]{1,4}$#', $day)) {
            return $default;
        }

        if ($time) {
            if (substr_count($time, ':') === 1) {
                $time .= ':00';
            }

            if (!preg_match('#^[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}$#', $time)) {
                return $default;
            }
        }

        $day = preg_split('#[/\-]#', $day);

        if (strlen($day[0]) !== 4) {
            $day = array_reverse($day);
        }

        return trim(implode('-', $day).' '.$time);
    }

    /**
     * @param string $format_from
     * @param string $date
     * @param string $timezone
     * @param string $format_to = 'Y-m-d H:i:s'
     *
     * @return string
     */
    public function dateUtcToTimezone(string $format_from, string $date, string $timezone, string $format_to = 'Y-m-d H:i:s'): string
    {
        return $this->dateToTimezone($format_from, $date, 'UTC', $timezone, $format_to);
    }

    /**
     * @param string $format_from
     * @param string $date
     * @param string $timezone
     * @param string $format_to = 'Y-m-d H:i:s'
     *
     * @return string
     */
    public function dateTimezoneToUtc(string $format_from, string $date, string $timezone, string $format_to = 'Y-m-d H:i:s'): string
    {
        return $this->dateToTimezone($format_from, $date, $timezone, 'UTC', $format_to);
    }

    /**
     * @param string $format_from
     * @param string $date
     * @param string $timezone_from
     * @param string $timezone_to
     * @param string $format_to = 'c'
     *
     * @return string
     */
    public function dateToTimezone(
        string $format_from,
        string $date,
        string $timezone_from,
        string $timezone_to,
        string $format_to = 'c'
    ): string {
        if ((substr_count($date, ':') === 1) && (substr_count($format_from, ':') === 2)) {
            $date .= ':00';
        }

        if (str_starts_with($format_from, 'Y') && preg_match('/^[0-9]{2}[\/\-]/', $date)) {
            $date = $this->dateToDate($date);
        }

        if (str_contains($date, '+') && (str_contains($format_from, '+') === false)) {
            $format_from .= 'O';
        }

        $timezone = $this->dateTimeZone($timezone_from);
        $datetime = DateTime::createFromFormat($format_from, $date, $timezone);

        try {
            $datetime = $datetime ?: new DateTime($date, $timezone);
        } catch (Throwable $e) {
            $datetime = new DateTime('now', $timezone);
        }

        return $datetime->setTimezone($this->dateTimeZone($timezone_to))->format($format_to);
    }

    /**
     * @param ?string $date = null
     * @param ?string $timezone = null
     *
     * @return \Datetime
     */
    public function dateTimeWithTimezone(?string $date = null, ?string $timezone = null): Datetime
    {
        $timezone = $this->dateTimeZone($timezone);

        try {
            return new DateTime($date ?: 'now', $timezone);
        } catch (Throwable $e) {
            return new DateTime('now', $timezone);
        }
    }

    /**
     * @param string $date
     * @param string $timezone
     * @param string $format = 'Y-m-d H:i:s'
     *
     * @return string
     */
    public function dateFormattedToTimezone(string $date, string $timezone, string $format = 'Y-m-d H:i:s'): string
    {
        return $this->dateTimeWithTimezone($date, $timezone)->format($format);
    }

    /**
     * @param string $timezone
     *
     * @return \DateTimeZone
     */
    public function dateTimeZone(string $timezone): DateTimeZone
    {
        static $cache;

        return $cache[$timezone] ??= new DateTimeZone($timezone);
    }

    /**
     * @param ?string $country
     *
     * @return array
     */
    public function countryTimezones(?string $country): array
    {
        if ($country === null) {
            return DateTimeZone::listIdentifiers();
        }

        return DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, strtoupper($country))
            ?: DateTimeZone::listIdentifiers();
    }

    /**
     * @param string $locale = 'es_ES'
     *
     * @return array
     */
    public function months(string $locale = 'es_ES'): array
    {
        static $cache = [];

        if (isset($cache[$locale])) {
            return $cache[$locale];
        }

        $format = function ($index) use ($locale) {
            $formatter = new IntlDateFormatter($locale);
            $formatter->setPattern('MMMM');

            return ucfirst($formatter->format(mktime(0, 0, 0, $index)));
        };

        return $cache[$locale] = array_combine(
            range(1, 12),
            array_map(static fn ($index) => $format($index), range(1, 12))
        );
    }
}
