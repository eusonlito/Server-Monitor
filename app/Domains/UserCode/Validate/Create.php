<?php declare(strict_types=1);

namespace App\Domains\UserCode\Validate;

use App\Domains\CoreApp\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['bail', 'string', 'required'],
            'expired_at' => ['bail', 'date_format:Y-m-d H:i:s', 'nullable'],
            'user_id' => ['bail', 'integer', 'required'],
        ];
    }
}
