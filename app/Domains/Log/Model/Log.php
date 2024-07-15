<?php declare(strict_types=1);

namespace App\Domains\Log\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domains\Log\Model\Builder\Log as Builder;
use App\Domains\Log\Model\Collection\Log as Collection;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\User\Model\User as UserModel;

class Log extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'log';

    /**
     * @const string
     */
    public const TABLE = 'log';

    /**
     * @const string
     */
    public const FOREIGN = 'log_id';

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * @param array $models
     *
     * @return \App\Domains\Log\Model\Collection\Log
     */
    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \App\Domains\Log\Model\Builder\Log
     */
    public function newEloquentBuilder($query): Builder
    {
        return new Builder($query);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, UserModel::FOREIGN);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parent(): HasMany
    {
        return $this->hasMany(Log::class, static::FOREIGN);
    }
}
