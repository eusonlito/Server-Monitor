<?php declare(strict_types=1);

namespace App\Domains\Profile\Validate;

use App\Domains\CoreApp\Validate\ValidateAbstract;

class Update extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['bail', 'required'],
            'email' => ['bail', 'email:filter', 'required'],
            'password' => ['bail', 'min:8'],
            'language_id' => ['bail', 'integer', 'required'],
        ];
    }
}
