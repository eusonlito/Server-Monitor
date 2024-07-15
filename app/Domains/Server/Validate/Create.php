<?php declare(strict_types=1);

namespace App\Domains\Server\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'string', 'required'],
            'ip' => ['bail', 'string'],
            'auth' => ['bail', 'uuid', 'required'],
            'enabled' => ['bail', 'boolean'],
            'dashboard' => ['bail', 'boolean'],
        ];
    }
}
