<?php declare(strict_types=1);

namespace App\Domains\Server\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Domains\Server\Service\Controller\UpdateSetup as ControllerService;

class UpdateSetup extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): Response|RedirectResponse
    {
        $this->row($id);

        $this->meta('title', __('server-update-setup.meta-title', ['title' => $this->row->name]));

        return $this->page('server.update-setup', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth, $this->row)->data();
    }
}
