<?php declare(strict_types=1);

namespace App\Domains\CoreApp\Validate;

use App\Domains\Core\Validate\ValidateAbstract as ValidateAbstractCore;
use App\Domains\Core\Validate\Rule\CSRF;

abstract class ValidateAbstract extends ValidateAbstractCore
{
    /**
     * @return \App\Domains\Core\Validate\Rule\CSRF
     */
    protected function ruleCsrf(): CSRF
    {
        return new CSRF();
    }
}
