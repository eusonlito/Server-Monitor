<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Model\Collection\Server as Collection;
use App\Domains\Server\Model\Server as Model;

class MeasureRetentionAll extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->iterate();
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->list() as $row) {
            $this->factory(row: $row)->action()->measureRetention();
        }
    }

    /**
     * @return \App\Domains\Server\Model\Collection\Server
     */
    protected function list(): Collection
    {
        return Model::query()
            ->enabled()
            ->whenMeasureRetention()
            ->get();
    }
}
