<?php declare(strict_types=1);

namespace App\Domains\User\Fractal;

use App\Domains\Core\Fractal\FractalAbstract;
use App\Domains\User\Model\User as Model;

class FractalFactory extends FractalAbstract
{
    /**
     * @param \App\Domains\User\Model\User $row
     *
     * @return array
     */
    protected function json(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
            'email' => $row->email,
            'enabled' => $row->enabled,

            'language' => $this->from('Language', 'related', $row->language),
        ];
    }

    /**
     * @param \App\Domains\User\Model\User $row
     *
     * @return array
     */
    protected function related(Model $row): array
    {
        return [
            'id' => $row->id,
            'name' => $row->name,
        ];
    }
}
