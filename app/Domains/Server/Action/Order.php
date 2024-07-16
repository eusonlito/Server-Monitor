<?php declare(strict_types=1);

namespace App\Domains\Server\Action;

use App\Domains\Server\Model\Server as Model;

class Order extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->save();
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->saveReset();
        $this->saveIterate();
    }

    /**
     * @return void
     */
    protected function saveReset(): void
    {
        Model::query()
            ->byIdsNot($this->data['ids'])
            ->update(['order' => 0]);
    }

    /**
     * @return void
     */
    protected function saveIterate(): void
    {
        foreach ($this->data['ids'] as $index => $id) {
            $this->saveIndexId($index, $id);
        }
    }

    /**
     * @param int $index
     * @param int $id
     *
     * @return void
     */
    protected function saveIndexId(int $index, int $id): void
    {
        Model::query()
            ->byId($id)
            ->update(['order' => $index + 1]);
    }
}
