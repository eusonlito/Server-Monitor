<?php declare(strict_types=1);

namespace App\Domains\Measure\Validate;

use App\Domains\Core\Validate\ValidateAbstract;

class CreateDiskBulk extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'disks' => ['bail', 'array', 'required'],
            'disks.*.filesystem' => ['bail', 'string', 'required'],
            'disks.*.size' => ['bail', 'integer', 'required'],
            'disks.*.used' => ['bail', 'integer'],
            'disks.*.available' => ['bail', 'integer'],
            'disks.*.percent' => ['bail', 'integer'],
            'disks.*.mount' => ['bail', 'string'],
        ];
    }
}
