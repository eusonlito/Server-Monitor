<?php declare(strict_types=1);

namespace App\Domains\Measure\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class CreateAppBulk extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'apps' => ['bail', 'array', 'required'],
            'apps.*.pid' => ['bail', 'integer', 'required'],
            'apps.*.user' => ['bail', 'string', 'required'],
            'apps.*.memory_virtual' => ['bail', 'integer', 'required'],
            'apps.*.memory_resident' => ['bail', 'integer', 'required'],
            'apps.*.memory_percent' => ['bail', 'numeric'],
            'apps.*.cpu_load' => ['bail', 'numeric'],
            'apps.*.cpu_percent' => ['bail', 'numeric'],
            'apps.*.time' => ['bail', 'integer'],
            'apps.*.command' => ['bail', 'string', 'required'],
        ];
    }
}
