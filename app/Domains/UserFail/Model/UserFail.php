<?php declare(strict_types=1);

namespace App\Domains\UserFail\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\CoreApp\Model\ModelAbstract;
use App\Domains\User\Model\User as UserModel;
use App\Domains\UserFail\Model\Builder\UserFail as Builder;
use App\Domains\UserFail\Model\Collection\UserFail as Collection;

class UserFail extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'user_fail';

    /**
     * @const string
     */
    public const TABLE = 'user_fail';

    /**
     * @const string
     */
    public const FOREIGN = 'user_fail_id';

    /**
     * @param array $models
     *
     * @return \App\Domains\UserFail\Model\Collection\UserFail
     */
    public function newCollection(array $models = []): Collection
    {
        return new Collection($models);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \App\Domains\UserFail\Model\Builder\UserFail
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
