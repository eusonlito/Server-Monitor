<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use Illuminate\Http\Response;
use App\Domains\Server\Service\Controller\UpdateMeasureApp as ControllerService;

class UpdateMeasureApp extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(int $id): Response
    {
        $this->row($id);

        $this->meta('title', __('server-update-measure-app.meta-title', ['title' => $this->row->name]));

        return $this->page('server.update-measure-app', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth, $this->row)->data();
    }
}
