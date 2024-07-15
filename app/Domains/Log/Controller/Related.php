<?php declare(strict_types=1);

namespace App\Domains\Log\Controller;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Log\Model\Log as Model;

class Related extends ControllerAbstract
{
    /**
     * @param string $code
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $code, int $id): Response
    {
        $this->meta('title', __('log-related.meta-title'));

        return $this->page('log.related', [
            'code' => $code,
            'id' => $id,
            'table' => ($table = str_replace('-', '_', $code)),
            'list' => $this->list($table, $id),
            'search' => $this->request->input('search'),
            'actions' => $this->actions($table, $id),
        ]);
    }

    /**
     * @param string $table
     * @param int $id
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list(string $table, int $id): LengthAwarePaginator
    {
        return Model::query()
            ->simple()
            ->whenSearch($this->request->input('search'))
            ->whenAction($this->request->input('action'))
            ->byRelatedTable($table)
            ->byRelatedId($id)
            ->withRelated()
            ->withUser()
            ->paginate(20);
    }

    /**
     * @param string $table
     * @param int $id
     *
     * @return array
     */
    public function actions(string $table, int $id): array
    {
        return Model::query()
            ->byRelatedTable($table)
            ->byRelatedId($id)
            ->groupByAction()
            ->pluck('action')
            ->all();
    }
}
