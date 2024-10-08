<?php declare(strict_types=1);

namespace App\Domains\CoreTranslation\Service;

use App\Services\Translator\TranslatorFactory;

class Translate extends ServiceAbstract
{
    /**
     * @var string
     */
    protected string $base = 'resources/lang';

    /**
     * @param string $from
     * @param array $to
     *
     * @return void
     */
    public function __construct(protected string $from, protected array $to)
    {
        $this->base();
        $this->to();
    }

    /**
     * @return void
     */
    protected function base(): void
    {
        $this->base = base_path($this->base);
    }

    /**
     * @return void
     */
    protected function to(): void
    {
        $locales = config('app.locales');

        if ($this->to[0] === 'all') {
            $to = $locales;
        } else {
            $to = helper()->arrayValuesWhitelist($this->to, $locales);
        }

        $this->to = helper()->arrayValuesBlacklist($to, [$this->from]);
    }

    /**
     * @return void
     */
    public function write(): void
    {
        foreach ($this->files() as $file) {
            foreach ($this->to as $to) {
                $this->translate($to, $file);
            }
        }
    }

    /**
     * @return array
     */
    protected function files(): array
    {
        return glob($this->base.'/'.$this->from.'/*.php');
    }

    /**
     * @param string $to
     * @param string $name
     *
     * @return string
     */
    protected function file(string $to, string $name): string
    {
        return $this->base.'/'.$to.'/'.$name;
    }

    /**
     * @param string $to
     * @param string $from
     *
     * @return void
     */
    protected function translate(string $to, string $from): void
    {
        $file = $this->file($to, basename($from));
        $current = array_dot(is_file($file) ? (require $file) : []);
        $empty = helper()->arrayFilterRecursive($current, static fn ($value) => is_string($value) && empty($value));
        $strings = array_filter(array_intersect_key(array_dot(require $from), $empty));

        if (empty($strings)) {
            return;
        }

        $translated = $this->request($to, $strings);

        if (empty($translated)) {
            return;
        }

        $this->writeFile($file, $this->translateUndot($current, $strings, $translated));
    }

    /**
     * @param array $current
     * @param array $strings
     * @param array $translated
     *
     * @return array
     */
    protected function translateUndot(array $current, array $strings, array $translated): array
    {
        return $this->undot(array_merge($current, array_combine(array_keys($strings), $translated)));
    }

    /**
     * @param string $to
     * @param array $strings
     *
     * @return ?array
     */
    protected function request(string $to, array $strings): ?array
    {
        return TranslatorFactory::get()->array($this->from, $to, $strings);
    }
}
