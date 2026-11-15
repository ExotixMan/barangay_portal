<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Model;

class DatabaseBoolean implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?bool
    {
        if ($value instanceof Expression) {
            $value = $value->getValue($model->getConnection()->getQueryGrammar());
        }

        return $value === null ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null || $value instanceof Expression) {
            return $value;
        }
        $boolean = filter_var($value, FILTER_VALIDATE_BOOLEAN);

        // Laravel binds native PHP booleans as integers. PostgreSQL boolean
        // columns need boolean literals instead; SQLite accepts numeric bindings.
        return $model->getConnection()->getDriverName() === 'pgsql'
            ? ($boolean ? 'true' : 'false')
            : $boolean;
    }
}
