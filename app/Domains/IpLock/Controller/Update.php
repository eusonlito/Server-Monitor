<?php declare(strict_types=1);

namespace App\Domains\IpLock\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class Update extends ControllerAbstract
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $id): Response|RedirectResponse
    {
        $this->row($id);

        if ($response = $this->actionPost(['update', 'delete'])) {
            return $response;
        }

        $this->requestMergeWithRow();

        $this->meta('title', __('ip-lock-update.meta-title', ['title' => $this->row->ip]));

        return $this->page('ip-lock.update', [
            'row' => $this->row,
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function update(): RedirectResponse
    {
        $this->action()->update();

        $this->sessionMessage('success', __('ip-lock-update.success'));

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function delete(): RedirectResponse
    {
        $this->action()->delete();

        $this->sessionMessage('success', __('ip-lock-update.delete-success'));

        return redirect()->route('ip-lock.index');
    }
}
