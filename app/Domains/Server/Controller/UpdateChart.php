<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use Illuminate\Http\Response;
use App\Domains\Server\Service\Controller\UpdateChart as ControllerService;

class UpdateChart extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $id): Response
    {
        $this->row($id);

        $this->meta('title', __('server-update-chart.meta-title', ['title' => $this->row->name]));

        return $this->page('server.update-chart', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth, $this->row)->data();
    }
}
