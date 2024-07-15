<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use Illuminate\Http\Response;
use App\Domains\Server\Service\Controller\UpdateMeasureUpdate as ControllerService;

class UpdateMeasureUpdate extends ControllerAbstract
{
    /**
     * @param int $id
     * @param int $measure_id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $id, int $measure_id): Response
    {
        $this->row($id);
        $this->measure($measure_id);

        $this->meta('title', __('server-update-measure-update.meta-title', ['title' => $this->row->name]));

        return $this->page('server.update-measure-update', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth, $this->row, $this->measure)->data();
    }
}
