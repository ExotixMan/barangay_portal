<?php

namespace App\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ResidentName
{
    public const PATTERN = "/^[\\p{L}\\p{M}\\s'’.,-]+$/u";

    public static function isFullNameField(string $field): bool
    {
        return in_array(strtolower(trim($field)), ['full name', 'full_name', 'fullname'], true);
    }

    public static function normalize(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    /** Confirm explicit parts rather than guessing where compound names split. */
    public static function confirmedParts(array $input, string $requestedName): array
    {
        $rules = [];
        $labels = [];
        foreach (['firstname' => 'first name', 'middlename' => 'middle name', 'lastname' => 'last name', 'suffix' => 'suffix'] as $part => $label) {
            $rules['name_'.$part] = [in_array($part, ['firstname', 'lastname'], true) ? 'required' : 'nullable', 'string', 'max:'.($part === 'suffix' ? '10' : '255'), 'regex:'.self::PATTERN];
            $labels['name_'.$part] = $label;
        }
        $validated = Validator::make($input, $rules, [
            'required' => 'Please enter the :attribute before approving the full-name correction.',
            'regex' => 'The :attribute may only contain letters, spaces, apostrophes, dots, commas, and hyphens.',
        ], $labels)->validate();

        $parts = [];
        foreach (array_keys($labels) as $field) {
            $value = self::normalize((string) ($validated[$field] ?? ''));
            $parts[substr($field, strlen('name_'))] = $value !== '' ? $value : null;
        }
        $confirmed = implode(' ', array_filter($parts));
        if (mb_strtolower($confirmed) !== mb_strtolower(self::normalize($requestedName))) {
            throw ValidationException::withMessages([
                'name_firstname' => 'The confirmed name parts must match the requested full name. Please check each part before approving.',
            ]);
        }

        return $parts;
    }
}
