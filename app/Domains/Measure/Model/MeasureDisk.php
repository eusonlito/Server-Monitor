<?php declare(strict_types=1);

namespace App\Domains\Measure\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\Measure\Model\Builder\MeasureDisk as Builder;
use App\Domains\Measure\Model\Collection\MeasureDisk as Collection;
use App\Domains\CoreApp\Model\ModelAbstract;

class MeasureDisk extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'measure_disk';

    /**
     * @const string
     */
    public const TABLE = 'measure_disk';

    /**
     * @const string
     */
    public const FOREIGN = 'measure_disk_id';

    /**
     * @param array $models
     *
     * @return \App\Domains\Measure\Model\Collection\Measure
     */
    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \App\Domains\Measure\Model\Builder\Measure
     */
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function measure(): BelongsTo
    {
        return $this->belongsTo(Measure::class, Measure::FOREIGN);
    }
}
