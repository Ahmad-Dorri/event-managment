<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait CanLoadRelationShips
{

    public function loadRelationships(Model|QueryBuilder|EloquentBuilder|HasMany $for, ?array $relationships = null): Model|QueryBuilder|EloquentBuilder|HasMany
    {
        $relationships = $relationships ?? $this->relations ?? [];
        $query = $for->query();
        foreach ($relationships as $relationship) {
            $query->when($this->shouldIncludeRelation($relationship), function ($q) use ($relationship, $for) {
                $for instanceof Model
                    ?
                    $for->load($relationship)
                    :
                    $q->with($relationship);
            });
        }

        return $query;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include');
        if (! $include) {
            return false;
        }
        $relations = array_map('trim', explode(',', $include));
        return in_array($relation, $relations);
    }
}
