<?php declare(strict_types=1);

namespace App\Domains\Server\Service\Parser;

class TopAbstract extends ParserAbstract
{
    /**
     * @param string $top
     * @param int $cores
     *
     * @return void
     */
    public function __construct(protected string $top, protected int $cores)
    {
    }
}
