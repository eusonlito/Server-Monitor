<?php declare(strict_types=1);

namespace App\Domains\User\Model\Builder;

use App\Domains\CoreApp\Model\Builder\BuilderAbstract;

class User extends BuilderAbstract
{
    /**
     * @var array
     */
    protected array $relationOrder = ['name', 'ASC'];

    /**
     * @var array
     */
    protected array $relationSelect = [
        'id', 'name', 'email',
    ];

    /**
     * @var array
     */
    protected array $simpleOrder = [
        'name', 'email', 'enabled', 'created_at', 'updated_at',
    ];

    /**
     * @var array
     */
    protected array $simpleOrderDefault = ['id', 'DESC'];

    /**
     * @var array
     */
    protected array $simpleSelect = [
        'id', 'name', 'email', 'enabled', 'created_at', 'updated_at',
    ];

    /**
     * @var array
     */
    protected array $searchLike = [
        'name', 'email',
    ];

    /**
     * @param string $email
     *
     * @return self
     */
    public function byEmail(string $email): self
    {
        return $this->where('email', strtolower($email));
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function byEmailLike(string $email): self
    {
        return $this->where('email', 'ILIKE', $email);
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->orderBy('name', 'ASC');
    }

    /**
     * @return self
     */
    public function listSimple(): self
    {
        return $this->selectRelated()->orderBy('name', 'ASC');
    }

    /**
     * @return self
     */
    public function selectRelated(): self
    {
        return $this->selectOnly('id', 'name');
    }
}
