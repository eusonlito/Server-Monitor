<?php declare(strict_types=1);

namespace App\Domains\Server\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class Order extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'ids' => ['bail', 'array', 'required'],
            'ids.*' => ['bail', 'integer'],
        ];
    }
}
