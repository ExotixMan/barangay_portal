<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class CaseInsensitiveUnique implements ValidationRule
{
    public function __construct(private string $table, private string $column, private ?int $ignore = null)
    {
        // Identifiers are application constants, never request input.
        if (! in_array($table, ['residents', 'admin_users'], true) || ! in_array($column, ['username', 'email'], true)) {
            throw new \InvalidArgumentException('Unsupported identity column.');
        }
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (DB::table($this->table)->whereRaw('LOWER('.$this->column.') = ?', [mb_strtolower(trim((string) $value))])
            ->when($this->ignore, fn ($q) => $q->where('id', '!=', $this->ignore))->exists()) {
            $fail('This '.str_replace('_', ' ', $attribute).' is already registered to another account.');
        }
    }
}
