<?php declare(strict_types=1);

namespace App\Domains\Log\Controller;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Log\Model\Log as Model;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->meta('title', __('log-index.meta-title'));

        return $this->page('log.index', [
            'list' => $this->list(),
            'search' => $this->request->input('search'),
            'actions' => $this->actions(),
            'related_tables' => $this->relatedTables(),
        ]);
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        return Model::query()
            ->simple()
            ->whenSearch($this->request->input('search'))
            ->whenAction($this->request->input('action'))
            ->whenRelatedTable($this->request->input('related_table'))
            ->whenRelatedId((int)$this->request->input('related_id'))
            ->withRelated()
            ->withUser()
            ->paginate(20);
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return Model::query()
            ->groupByAction()
            ->pluck('action')
            ->all();
    }

    /**
     * @return array
     */
    public function relatedTables(): array
    {
        return Model::query()
            ->groupByRelatedTable()
            ->pluck('related_table')
            ->all();
    }
}
