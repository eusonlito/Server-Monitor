<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Service\Controller;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use App\Domains\Server\Model\Collection\Server as ServerCollection;
use App\Domains\Server\Model\Server as ServerModel;

class Index extends ControllerAbstract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $auth
     *
     * @return void
     */
    public function __construct(protected Request $request, protected Authenticatable $auth)
    {
        $this->requestMergePreference();
    }

    /**
     * @return void
     */
    protected function requestMergePreference(): void
    {
        $this->request->merge([
            'order' => $this->auth->preference('dashboard-order', $this->request->input('order'), 'manual'),
        ]);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            'servers' => $this->servers(),
            'order_options' => $this->orderOptions(),
            'order' => $this->request->input('order'),
        ];
    }

    /**
     * @return \App\Domains\Server\Model\Collection\Server
     */
    protected function servers(): ServerCollection
    {
        return ServerModel::query()
            ->enabled()
            ->whereDashboard()
            ->withMeasure()
            ->list()
            ->get()
            ->pipe($this->serversOrder(...));
    }

    /**
     * @param \App\Domains\Server\Model\Collection\Server $collection
     *
     * @return \App\Domains\Server\Model\Collection\Server
     */
    protected function serversOrder(ServerCollection $collection): ServerCollection
    {
        return match ($order = $this->request->input('order')) {
            'name' => $collection->sortBy('name'),
            'memory' => $collection->sortByDesc('measure.memory_percent'),
            'cpu' => $collection->sortByDesc('measure.cpu_percent'),
            'disk' => $collection->sortByDesc('measure.disk.percent'),
            default => $collection,
        };
    }

    /**
     * @return array
     */
    protected function orderOptions(): array
    {
        return [
            'name' => __('dashboard-index.order-name'),
            'memory' => __('dashboard-index.order-memory'),
            'cpu' => __('dashboard-index.order-cpu'),
            'disk' => __('dashboard-index.order-disk'),
            'manual' => __('dashboard-index.order-manual'),
        ];
    }
}
