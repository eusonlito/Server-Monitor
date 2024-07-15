<?php declare(strict_types=1);

namespace App\Domains\Language\Fractal;

use App\Domains\Core\Fractal\FractalAbstract;
use App\Domains\Language\Model\Language as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\Language\Model\Language $row
     *
     * @return array
     */
    protected function json(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'code' => $row->code,
            'iso' => $row->iso,
            'rtl' => $row->rtl,
        ];
    }

    /**
     * @param \App\Domains\Language\Model\Language $row
     *
     * @return array
     */
    protected function related(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'code' => $row->code,
            'iso' => $row->iso,
            'rtl' => $row->rtl,
        ];
    }
}
