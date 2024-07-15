<?php declare(strict_types=1);

namespace App\Domains\UserCode\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\CoreApp\Model\ModelAbstract;
use App\Domains\User\Model\User as UserModel;
use App\Domains\UserCode\Model\Builder\UserCode as Builder;
use App\Domains\UserCode\Model\Collection\UserCode as Collection;

class UserCode extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'user_code';

    /**
     * @const string
     */
    public const TABLE = 'user_code';

    /**
     * @const string
     */
    public const FOREIGN = 'user_code_id';

    /**
     * @param array $models
     *
     * @return \App\Domains\UserCode\Model\Collection\UserCode
     */
    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \App\Domains\UserCode\Model\Builder\UserCode
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
}
