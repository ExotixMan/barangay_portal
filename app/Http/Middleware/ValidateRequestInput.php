<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/** Check input shapes before controllers normalize text or build page filters. */
class ValidateRequestInput
{
    private const SCALAR_FIELDS = [
        'firstname', 'middlename', 'lastname', 'first_name', 'middle_name', 'last_name', 'suffix',
        'username', 'email', 'login', 'password', 'password_confirmation', 'current_password',
        'new_password', 'new_password_confirmation', 'contact', 'contact_number', 'phone',
        'complainantContact', 'respondentContact', 'title', 'name', 'description', 'message',
        'remarks', 'status', 'correct_value', 'current_value', 'field_name', 'reference_number',
        'birthdate', 'gender', 'civil_status', 'address', 'place_of_birth', 'birth_place',
        'purpose', 'purpose_other', 'monthly_income', 'household_members', 'service',
        'resolution_status', 'request_type', 'resident_id', 'request_id', 'correction_id',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $rules = [];
        $scalar = static function ($attribute, $value, $fail) {
            if (is_array($value) || is_object($value)) {
                $fail('Please provide a single value for '.str_replace('_', ' ', $attribute).'.');
            }
        };
        foreach (self::SCALAR_FIELDS as $field) {
            if ($request->exists($field)) {
                $rules[$field] = [$scalar];
            }
        }
        foreach ($request->query() as $field => $value) {
            $rules[$field] = [$scalar];
        }
        foreach (['resident_id', 'request_id', 'correction_id', ...($request->isMethod('GET') ? ['id', 'role_id', 'selected_role', 'log_user_id'] : [])] as $field) {
            if ($request->exists($field)) {
                $rules[$field] = ['nullable', 'integer', 'min:1', 'max:'.PHP_INT_MAX];
            }
        }
        if ($request->isMethod('GET')) {
            $filters = [
                'direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
                'search' => ['nullable', 'string', 'max:255'],
                'sort' => ['nullable', 'string', 'max:100'],
                'page' => ['nullable', 'integer', 'min:1', 'max:1000000'],
                'logs_page' => ['nullable', 'integer', 'min:1', 'max:1000000'],
                'log_date' => ['nullable', 'date_format:Y-m-d'],
                'month' => ['nullable', 'integer', 'between:1,12'],
                'year' => ['nullable', 'integer', 'between:1,9999'],
                'from_date' => ['nullable', 'date_format:Y-m-d'],
                'to_date' => ['nullable', 'date_format:Y-m-d'],
                'date_from' => ['nullable', 'date_format:Y-m-d'],
                'date_to' => ['nullable', 'date_format:Y-m-d'],
            ];
            foreach ($filters as $field => $fieldRules) {
                if ($request->query->has($field)) {
                    $rules[$field] = $fieldRules;
                }
            }
            foreach (['to_date' => 'from_date', 'date_to' => 'date_from'] as $end => $start) {
                if ($request->filled($start) && $request->filled($end)) {
                    $rules[$end][] = 'after_or_equal:'.$start;
                }
            }
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Please check the submitted values.', 'errors' => $validator->errors()], 422);
            }
            if ($request->isMethod('GET')) {
                // Drop invalid filters instead of redirecting to the same broken query.
                return redirect()->to($request->url())->withErrors($validator);
            }
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
