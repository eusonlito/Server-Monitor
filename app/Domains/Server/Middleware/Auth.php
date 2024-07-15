<?php declare(strict_types=1);

namespace App\Domains\Server\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Auth extends MiddlewareAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (app()->bound('debugbar')) {
            app('debugbar')->disable();
        }

        try {
            $this->factory()->action()->auth();
        } catch (Exception $e) {
            return $this->fail($e);
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function fail(Exception $e): Response
    {
        report($e);

        return response('Unauthorized.', 401);
    }
}
