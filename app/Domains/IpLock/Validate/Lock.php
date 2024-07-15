<?php declare(strict_types=1);

namespace App\Domains\IpLock\Validate;

use App\Domains\CoreApp\Validate\ValidateAbstract;

class Lock extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'ip' => ['bail', 'required', 'ip'],
        ];
    }
}
