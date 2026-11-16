<?php

namespace App\Rules;

use App\Support\ResidentName;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/** Reject unusable corrections before they lock the resident's service forms. */
class ProfileCorrectionValue implements ValidationRule
{
    public function __construct(private string $field) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $field = strtolower(str_replace('_', ' ', trim($this->field)));
        $rules = match ($field) {
            'full name', 'fullname' => ['max:1000', 'regex:'.ResidentName::PATTERN, 'regex:/\p{L}/u'],
            'firstname', 'first name', 'middlename', 'middle name', 'lastname', 'last name', 'place of birth', 'birth place' => ['max:255', 'regex:'.ResidentName::PATTERN, 'regex:/\p{L}/u'],
            'suffix' => ['max:10', 'regex:'.ResidentName::PATTERN],
            'birthdate', 'birth date' => ['date_format:Y-m-d', 'before:today', 'before_or_equal:'.now()->subYearsNoOverflow(18)->toDateString()],
            'contact', 'contact number' => ['regex:/^09\d{9}$/'],
            'email', 'email address' => ['email', 'max:255'],
            'gender' => [Rule::in(['Male', 'Female', 'Prefer not to say'])],
            'civil status' => [Rule::in(['Single', 'Married', 'Widowed', 'Separated'])],
            default => ['max:255'],
        };
        $validator = Validator::make(['correction' => $value], ['correction' => ['bail', 'string', ...$rules]], [
            'correction.max' => 'The corrected value must not exceed :max characters.',
            'correction.regex' => match ($field) {
                'contact', 'contact number' => 'Enter a Philippine mobile number in the format 09XXXXXXXXX.',
                default => 'Enter a name or place using letters, spaces, apostrophes, dots, commas, or hyphens.',
            },
            'correction.date_format' => 'Enter the birthdate in YYYY-MM-DD format.',
            'correction.before' => 'The birthdate must be before today.',
            'correction.before_or_equal' => 'The resident must be at least 18 years old.',
            'correction.email' => 'Enter a valid corrected email address.',
            'correction.in' => 'Enter one of the available profile options for this field.',
        ]);
        if ($validator->fails()) {
            $fail($validator->errors()->first('correction'));
        }
    }
}
