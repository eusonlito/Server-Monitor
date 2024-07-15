<?php declare(strict_types=1);

namespace App\Domains\Server\Exception;

use App\Exceptions\AuthenticationException;

class AuthFailed extends AuthenticationException
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var ?string
     */
    protected ?string $status = 'server-auth-failed';
}
