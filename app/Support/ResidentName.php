<?php

namespace App\Support;

use App\Models\Residents;
use Illuminate\Support\Facades\Validator;

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

    /** Editable suggestions for legacy requests that contain a single full name. */
    public static function suggestedParts(string $requested, Residents $resident): array
    {
        $name = self::normalize($requested);
        $parts = ['firstname' => '', 'middlename' => null, 'lastname' => '', 'suffix' => null];
        $tokens = explode(' ', $name);
        if (count($tokens) > 2 && preg_match('/^(jr\.?|sr\.?|ii|iii|iv|v|vi|vii|viii|ix|x)$/iu', end($tokens))) {
            $parts['suffix'] = array_pop($tokens);
        }
        $name = implode(' ', $tokens);
        $lastName = self::normalize((string) $resident->lastname);
        if ($lastName !== '' && preg_match('/^(.+)\s('.preg_quote($lastName, '/').')$/iu', $name, $match)) {
            $parts['lastname'] = $match[2];
            $given = $match[1];
        } else {
            $last = array_pop($tokens) ?? '';
            $particles = ['de', 'del', 'dela', 'delos', 'delas', 'de la', 'de los', 'de las', 'da', 'dos', 'das', 'di', 'du', 'van', 'van de', 'van der', 'von', 'san', 'santa', 'santo'];
            for ($length = min(2, count($tokens) - 1); $length > 0; $length--) {
                $prefix = implode(' ', array_slice($tokens, -$length));
                if (in_array(mb_strtolower($prefix), $particles, true)) {
                    $last = $prefix.' '.$last;
                    $tokens = array_slice($tokens, 0, -$length);
                    break;
                }
            }
            $parts['lastname'] = $last;
            $given = implode(' ', $tokens);
        }
        $middle = self::normalize((string) $resident->middlename);
        if ($middle !== '' && preg_match('/^(.+)\s('.preg_quote($middle, '/').')$/iu', $given, $match)) {
            $given = $match[1];
            $parts['middlename'] = $match[2];
        }
        $parts['firstname'] = $given;

        return $parts;
    }

    /** Confirm explicit parts rather than guessing where compound names split. */
    public static function confirmedParts(array $input): array
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

        return $parts;
    }
}
