<?php declare(strict_types=1);

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as KernelVendor;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use App\Domains\IpLock\Middleware\Check as IpLockCheck;
use App\Domains\Language\Middleware\Request as LanguageRequest;
use App\Domains\Server\Middleware\Auth as ServerAuth;
use App\Domains\User\Middleware\AuthRedirect as UserAuthRedirect;
use App\Domains\User\Middleware\Enabled as UserEnabled;
use App\Domains\User\Middleware\Request as UserRequest;
use App\Http\Middleware\MessagesShareFromSession;
use App\Http\Middleware\RequestLogger;
use App\Http\Middleware\Reset;

class Kernel extends KernelVendor
{
    /**
     * @var array<int, string>
     */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        RequestLogger::class,
        Reset::class,
        LanguageRequest::class,
    ];

    /**
     * @var array<string, array<int, string>>
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            MessagesShareFromSession::class,
        ],

        'user-auth' => [
            IpLockCheck::class,
            UserRequest::class,
            UserEnabled::class,
        ],
    ];

    /**
     * @var array<string, string>
     */
    protected $routeMiddleware = [
        'server.auth' => ServerAuth::class,
        'user.auth' => UserRequest::class,
        'user.auth.redirect' => UserAuthRedirect::class,
    ];
}
