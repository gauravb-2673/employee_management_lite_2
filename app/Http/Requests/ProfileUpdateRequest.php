<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id), // This rule ensures that the email address is unique in the users table, but it ignores the current user's email address. This allows the user to keep their existing email without triggering a validation error.
                // Rule is a class provided by Laravel that allows for more complex validation rules. In this case, it is used to create a unique rule for the email field, while ignoring the current user's ID. the  $this->user() method retrieves the currently authenticated user, and the ->id property gets their ID. This way, if the user is updating their profile and keeps their existing email, it won't be flagged as a duplicate. uses which model to check for uniqueness, in this case, the User model is used to check the users table for existing email addresses. the user object is retrieved from the request, and its ID is used to ignore the current user's email address during the uniqueness check.

                // The rule class provides a fluent interface for defining validation rules, making it easier to read and understand the validation logic. It allows for chaining multiple rules together, as seen in this example where the unique rule is combined with the ignore method to create a more specific validation requirement. different use cases for rules class are as follows: if you want to validate that a field is unique in the database, you can use the Rule::unique() method. If you want to ignore a specific record (like the current user's record), you can chain the ignore() method. You can also use other methods like where() to add additional conditions to the uniqueness check, or even create custom validation rules by extending the Rule class.
                // type of custom validation rules use methods like Rule::exists(), Rule::in(), Rule::notIn(), Rule::dimensions(), and Rule::prohibitedIf(), Rule::requiredIf(), Rule::requiredUnless(), Rule::requiredWith(), Rule::requiredWithout(), Rule::requiredWithAll(), Rule::requiredWithoutAll(), Rule::prohibitedIf(), Rule::prohibitedUnless(), Rule::prohibitedWith(), Rule::prohibitedWithout(), Rule::prohibitedWithAll(), Rule::prohibitedWithoutAll, Rule::unique(), Rule::exists(), Rule::in(), Rule::notIn()

                // each example with syntax and explanation:
                // Rule::exists('table', 'column') - Validates that the value exists in the specified table and column. For example, Rule::exists('users', 'id') checks if the provided value exists in the 'id' column of the 'users' table.
                // Rule::in(['value1', 'value2']) - Validates that the value is one of the      specified values. For example, Rule::in(['admin', 'user']) checks if the provided value is either 'admin' or 'user'.
                // Rule::notIn(['value1', 'value2']) - Validates that the value is not one of the specified values. For example, Rule::notIn(['banned', 'inactive']) checks if the provided value is neither 'banned' nor 'inactive'.
                // Rule::dimensions(['min_width' => 100, 'min_height' => 100]) - Validates that an uploaded image meets the specified minimum dimensions. For example, Rule::dimensions(['min_width' => 100, 'min_height' => 100]) checks if the uploaded image is at least 100 pixels wide and 100 pixels tall.
                // Rule::prohibitedIf('condition') - Validates that the field is prohibited if the specified condition is true. For example, Rule::prohibitedIf($user->isAdmin()) checks if the field is prohibited when the user is an admin.
                // Rule::requiredIf('condition') - Validates that the field is required if the specified condition is true. For example, Rule::requiredIf($user->isGuest()) checks if the field is required when the user is a guest.
                // Rule::requiredUnless('condition') - Validates that the field is required unless the specified condition is true. For example, Rule::requiredUnless($user->isActive()) checks if the field is required unless the user is active.
                // Rule::requiredWith('other_field') - Validates that the field is required if the specified other field is present. For example, Rule::requiredWith('password_confirmation') checks if the field is required when the 'password_confirmation' field is present.
                // Rule::requiredWithout('other_field') - Validates that the field is required if the specified other field is not present. For example, Rule::requiredWithout('email') checks if the field is required when the 'email' field is not present.
                // Rule::requiredWithAll('field1', 'field2') - Validates that the field is required if all of the specified other fields are present. For example, Rule::requiredWithAll('first_name', 'last_name') checks if the field is required when both 'first_name' and 'last_name' fields are present.
                // Rule::requiredWithoutAll('field1', 'field2') - Validates that the field is required if none of the specified other fields are present. For example, Rule::requiredWithoutAll('address', 'phone') checks if the field is required when neither 'address' nor 'phone' fields are present.
                // Rule::prohibitedWith('other_field') - Validates that the field is prohibited if the specified other field is present. For example, Rule::prohibitedWith('username') checks if the field is prohibited when the 'username' field is present.
                //
                // Rule::prohibitedWithout('other_field') - Validates that the field is prohibited if the specified other field is not present. For example, Rule::prohibitedWithout('email') checks if the field is prohibited when the 'email' field is not present.
                // Rule::prohibitedWithAll('field1', 'field2') - Validates that the field is prohibited if all of the specified other fields are present. For example, Rule::prohibitedWithAll('first_name', 'last_name') checks if the field is prohibited when both 'first_name' and 'last_name' fields are present.
                // Rule::prohibitedWithoutAll('field1', 'field2') - Validates that the field is prohibited if none of the specified other fields are present. For example, Rule::prohibitedWithoutAll('address', 'phone') checks if the field is prohibited when neither 'address' nor 'phone' fields are present.
                // Rule::unique('table', 'column') - Validates that the value is unique in the specified table and column. For example, Rule::unique('users', 'email') checks if the provided email address is unique in the 'email' column of the 'users' table.
                // Rule::exists('table', 'column') - Validates that the value exists in the specified table and column. For example, Rule::exists('users', 'id') checks if the provided value exists in the 'id' column of the 'users' table.
                // Rule::in(['value1', 'value2']) - Validates that the value is one of the specified values. For example, Rule::in(['admin', 'user']) checks if the provided value is either 'admin' or 'user'.
                // Rule::notIn(['value1', 'value2']) - Validates that the value is not one of the specified values. For example, Rule::notIn(['banned', 'inactive']) checks if the provided value is neither 'banned' nor 'inactive'.
                // Rule::dimensions(['min_width' => 100, 'min_height' => 100]) - Validates that an uploaded image meets the specified minimum dimensions. For example, Rule::dimensions(['min_width' => 100, 'min_height' => 100]) checks if the uploaded image is at least 100 pixels wide and 100 pixels tall.
            ],
        ];
    }
}
