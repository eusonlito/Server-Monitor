<?php declare(strict_types=1);

namespace App\Domains\Measure\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Measure\Model\Builder\Measure as Builder;
use App\Domains\Measure\Model\Collection\Measure as Collection;
use App\Domains\CoreApp\Model\ModelAbstract;

class Measure extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'measure';

    /**
     * @const string
     */
    public const TABLE = 'measure';

    /**
     * @const string
     */
    public const FOREIGN = 'measure_id';

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disks(): HasMany
    {
        return $this->hasMany(MeasureDisk::class, static::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disk(): BelongsTo
    {
        return $this->belongsTo(MeasureDisk::class, MeasureDisk::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(ServerModel::class, ServerModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function apps(): HasMany
    {
        return $this->hasMany(MeasureApp::class, static::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appCpu(): BelongsTo
    {
        return $this->belongsTo(MeasureApp::class, 'measure_app_cpu_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function appMemory(): BelongsTo
    {
        return $this->belongsTo(MeasureApp::class, 'measure_app_memory_id');
    }
}
