<?php declare(strict_types=1);

namespace App\Services\Mail;

use DateTime;
use DateTimeZone;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Symfony\Component\Mime\Email;

class Logger
{
    /**
     * @var bool
     */
    protected static bool $listen = false;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @return void
     */
    public function listen(): void
    {
        if ($this->enabled() === false) {
            return;
        }

        Event::listen(MessageSending::class, fn ($event) => $this->store($event));

        static::$listen = true;
    }

    /**
     * @return bool
     */
    protected function enabled(): bool
    {
        return (static::$listen === false)
            && (config('logging.channels.mail.enabled') === true);
    }

    /**
     * @param \Illuminate\Mail\Events\MessageSending $event
     *
     * @return void
     */
    protected function store(MessageSending $event): void
    {
        $file = $this->file();

        helper()->mkdir($file, true);

        file_put_contents($file, $this->headers($event->message)."\n\n".$this->body($event->message));
    }

    /**
     * @param \Symfony\Component\Mime\Email $message
     *
     * @return string
     */
    protected function headers(Email $message): string
    {
        return $message->getHeaders()->toString();
    }

    /**
     * @param \Symfony\Component\Mime\Email $message
     *
     * @return string
     */
    protected function body(Email $message): string
    {
        $body = htmlspecialchars_decode($message->getBody()->bodyToString());
        $body = preg_replace('/=\r\n/', '', $body);

        return str_replace('=3D', '=', $body);
    }

    /**
     * @return string
     */
    protected function file(): string
    {
        $date = new DateTime();
        $date->setTimezone(new DateTimeZone('UTC'));

        return storage_path('logs/mail/'.$date->format('Y/m/d/H-i-s-u').'.log');
    }
}
