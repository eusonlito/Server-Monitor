<?php declare(strict_types=1);

namespace App\Domains\Measure\Model\Traits;

trait MeasureChart
{
    /**
     * @param string $column
     *
     * @return array
     */
    public function chart(string $column): array
    {
        $range = (clone $this)->toBase()
            ->selectRaw('COUNT(*) AS count, MAX('.$this->addTable('id').') AS id')
            ->first();

        if (empty($range->count)) {
            return [];
        }

        $this->where($this->addTable('id'), '<=', $range->id);

        if ($range->count <= 2000) {
            return $this->orderByFirst()->pluck($column, 'created_at')->all();
        }

        // Preserve two extremes per bucket and both endpoints within 2000 points.
        $size = (int)ceil($range->count / 999);
        $points = [];
        $first = $last = $min = $max = null;
        $index = 0;

        foreach ($this->select($this->addTable(['id', $column, 'created_at']))->orderByFirst()->toBase()->cursor() as $row) {
            $first ??= $row;
            $last = $row;

            if (($index++ % $size) === 0) {
                if ($min) {
                    $points[$min->id] = $min;
                    $points[$max->id] = $max;
                }

                $min = $max = $row;
            } else {
                if ($row->$column < $min->$column) {
                    $min = $row;
                }

                if ($row->$column > $max->$column) {
                    $max = $row;
                }
            }
        }

        if ($first === null) {
            return [];
        }

        $points[$min->id] = $min;
        $points[$max->id] = $max;
        $points[$first->id] = $first;
        $points[$last->id] = $last;
        ksort($points);

        return array_column($points, $column, 'created_at');
    }
}
