<?php declare(strict_types=1);

namespace App\Domains\Log\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;

class Log extends BuilderAbstract
{
    /**
     * @param string $action
     *
     * @return self
     */
    public function byAction(string $action): self
    {
        return $this->where($this->addTable('action'), $action);
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public function byIp(string $ip): self
    {
        return $this->where('ip', $ip);
    }

    /**
     * @param string $related_table
     * @param int $related_id
     *
     * @return self
     */
    public function byRelated(string $related_table, int $related_id): self
    {
        return $this->byRelatedTable($related_table)->byRelatedId($related_id);
    }

    /**
     * @param string $related_table
     * @param array $related_ids
     *
     * @return self
     */
    public function byRelatedTableAndIds(string $related_table, array $related_ids): self
    {
        return $this->byRelatedTable($related_table)->byRelatedIds($related_ids);
    }

    /**
     * @param int $related_id
     *
     * @return self
     */
    public function byRelatedId(int $related_id): self
    {
        return $this->where($this->addTable('related_id'), $related_id);
    }

    /**
     * @param array $related_ids
     *
     * @return self
     */
    public function byRelatedIds(array $related_ids): self
    {
        return $this->whereIntegerInRaw($this->addTable('related_id'), $related_ids);
    }

    /**
     * @param string $related_table
     *
     * @return self
     */
    public function byRelatedTable(string $related_table): self
    {
        return $this->where($this->addTable('related_table'), $related_table);
    }

    /**
     * @return self
     */
    public function groupByAction(): self
    {
        return $this->groupBy($this->addTable('action'))->orderBy($this->addTable('action'));
    }

    /**
     * @return self
     */
    public function groupByRelatedTable(): self
    {
        return $this->groupBy($this->addTable('related_table'))->orderBy($this->addTable('related_table'));
    }

    /**
     * @param ?string $action
     *
     * @return self
     */
    public function whenAction(?string $action): self
    {
        return $this->when($action, fn ($q) => $q->byAction($action));
    }

    /**
     * @param ?string $created_at
     *
     * @return self
     */
    public function whenCreatedAtBefore(?string $created_at): self
    {
        return $this->when($created_at, fn ($q) => $q->byCreatedAtBefore($created_at));
    }

    /**
     * @param ?int $related_id
     *
     * @return self
     */
    public function whenRelatedId(?int $related_id): self
    {
        return $this->when($related_id, fn ($q) => $q->byRelatedId($related_id));
    }

    /**
     * @param ?string $related_table
     *
     * @return self
     */
    public function whenRelatedTable(?string $related_table): self
    {
        return $this->when($related_table, fn ($q) => $q->byRelatedTable($related_table));
    }

    /**
     * @param ?string $search
     *
     * @return self
     */
    public function whenSearch(?string $search): self
    {
        return $this->when($search, fn ($q) => $q->bySearch($search));
    }

    /**
     * @return self
     */
    public function withParent(): self
    {
        return $this->with('parent');
    }

    /**
     * @return self
     */
    public function withUser(): self
    {
        return $this->with('user');
    }
}
