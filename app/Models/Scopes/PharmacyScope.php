<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PharmacyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if ($pharmacyId = currentPharmacyId()) {
            $builder->where($model->qualifyColumn('pharmacy_id'), $pharmacyId);
        }
    }
}
