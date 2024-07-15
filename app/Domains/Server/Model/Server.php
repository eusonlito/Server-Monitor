<?php declare(strict_types=1);

namespace App\Domains\Server\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\CoreApp\Model\ModelAbstract;
use App\Domains\Measure\Model\Measure as MeasureModel;
use App\Domains\Server\Model\Builder\Server as Builder;
use App\Domains\Server\Model\Collection\Server as Collection;

class Server extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'server';

    /**
     * @const string
     */
    public const TABLE = 'server';

    /**
     * @const string
     */
    public const FOREIGN = 'server_id';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'debug' => 'boolean',
        'enabled' => 'boolean',
    ];

    /**
     * @param array $models
     *
     * @return \App\Domains\Server\Model\Collection\Server
     */
    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \App\Domains\Server\Model\Builder\Server
     */
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function measures(): HasMany
    {
        return $this->hasMany(MeasureModel::class, static::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function measure(): BelongsTo
    {
        return $this->belongsTo(MeasureModel::class, MeasureModel::FOREIGN);
    }
}
