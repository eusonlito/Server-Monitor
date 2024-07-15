<?php declare(strict_types=1);

namespace App\Domains\User\Validate;

class Update extends Create
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => ['bail', 'min:8'],
        ] + parent::rules();
    }
}
