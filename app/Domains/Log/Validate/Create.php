<?php declare(strict_types=1);

namespace App\Domains\Log\Validate;

use App\Domains\CoreApp\Validate\ValidateAbstract;

class Create extends ValidateAbstract
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'class' => ['bail', 'string'],
            'action' => ['bail', 'string', 'required_without:class'],
            'related' => ['bail', 'array', 'required'],
            'related.*.related_table' => ['bail', 'string', 'required'],
            'related.*.related_id' => ['bail', 'integer'],
            'related.*.payload' => ['bail', 'array'],
            'payload' => ['bail'],
        ];
    }
}
