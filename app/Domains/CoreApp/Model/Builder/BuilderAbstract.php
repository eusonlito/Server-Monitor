<?php declare(strict_types=1);

namespace App\Domains\CoreApp\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract as BuilderAbstractCore;

abstract class BuilderAbstract extends BuilderAbstractCore
{
    /**
     * @var array
     */
    protected array $simpleOrderDefault = ['id', 'DESC'];

    /**
     * @var array
     */
    protected array $simpleOrder = [];

    /**
     * @var array
     */
    protected array $simpleOrderByColumn = [];

    /**
     * @param string $class
     * @param string $column
     * @param ?string $mode
     *
     * @return self
     */
    public function orderByLeftJoin(string $class, string $column, ?string $mode): self
    {
        $table = $class::TABLE;
        $foreign = $class::FOREIGN;

        return $this->leftJoin($table, $table.'.id', '=', $this->addTable($foreign))
            ->orderBy($table.'.'.$column, $this->orderMode($mode));
    }

    /**
     * @return self
     */
    public function relation(): self
    {
        return $this->relationSelect()->relationOrder();
    }

    /**
     * @return self
     */
    public function relationOrder(): self
    {
        return $this->orderBy($this->addTable($this->relationOrder[0]), $this->relationOrder[1]);
    }

    /**
     * @return self
     */
    public function relationSelect(): self
    {
        return $this->select($this->addTable($this->relationSelect));
    }

    /**
     * @return self
     */
    public function simple(): self
    {
        return $this->simpleSelect()->simpleOrderDefault();
    }

    /**
     * @param ?string $column
     * @param ?string $mode
     * @param bool $default = true
     *
     * @return self
     */
    public function simpleOrder(?string $column, ?string $mode, bool $default = true): self
    {
        if (in_array($column, $this->simpleOrder) === false) {
            return $default ? $this->simpleOrderDefault() : $this;
        }

        if (in_array($column, $this->simpleOrderByColumn)) {
            return $this->orderByColumn($column, $mode);
        }

        return $this->orderByColumn($this->addTable($column), $mode);
    }

    /**
     * @return self
     */
    public function simpleOrderDefault(): self
    {
        return $this->orderByColumn($this->simpleOrderDefault[0], $this->simpleOrderDefault[1]);
    }

    /**
     * @return self
     */
    public function simpleSelect(): self
    {
        return $this->select($this->addTable($this->simpleSelect));
    }

    /**
     * @param ?string $column, ?string $mode
     *
     * @return self
     */
    public function whenOrder(?string $column, ?string $mode): self
    {
        return $this->simpleOrder($column, $mode);
    }

    /**
     * @param ?string $search
     *
     * @return self
     */
    public function whenSearch(?string $search): self
    {
        return $this->when($search, fn ($q) => $q->searchLike($this->searchLike, $search));
    }

    /**
     * @param string $relation
     *
     * @return self
     */
    public function withRelation(string $relation): self
    {
        return $this->with([$relation => fn ($q) => $q->relationSelect()]);
    }

    /**
     * @param string $relation
     * @param bool $condition
     *
     * @return self
     */
    public function withRelationWhen(string $relation, bool $condition): self
    {
        return $this->when($condition, fn ($q) => $q->withRelation($relation));
    }
}
