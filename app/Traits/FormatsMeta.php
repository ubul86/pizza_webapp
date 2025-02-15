<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
trait FormatsMeta
{
    /**
     * @param LengthAwarePaginator<TModel> $models
     * @return array<string, int>
     */
    protected function formatMeta(LengthAwarePaginator $models): array
    {
        return [
            'current_page' => $models->currentPage(),
            'total_pages' => $models->lastPage(),
            'total_items' => $models->total(),
            'items_per_page' => $models->perPage(),
        ];
    }
}
