<?php declare(strict_types=1);

namespace App\Domains\Log\Controller;

use Illuminate\Http\Response;

class Detail extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $id): Response
    {
        $this->row($id);

        $this->meta('title', $this->row->action);

        return $this->page('log.detail', [
            'row' => $this->row,
        ]);
    }
}
