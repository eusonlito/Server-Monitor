<?php declare(strict_types=1);

namespace App\Domains\Server\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class MeasureCreate extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'top' => ['bail', 'string'],
            'df' => ['bail', 'string'],
            'cores' => ['bail', 'integer'],
            'uptime' => ['bail', 'string'],
        ];
    }
}
