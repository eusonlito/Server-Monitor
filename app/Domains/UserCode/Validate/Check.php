<?php declare(strict_types=1);

namespace App\Domains\UserCode\Validate;

use App\Domains\CoreApp\Validate\ValidateAbstract;

class Check extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['bail', 'string', 'required'],
            'code' => ['bail', 'string', 'required'],
            'user_id' => ['bail', 'integer', 'required'],
            'finish' => ['bail', 'bool'],
        ];
    }
}
