<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'employees';

    protected $fillable = [
        'department_id',
        'name',
        'email',
        'salary',
        'joining_date',
        'is_active',
        'user_id',
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->using(EmployeeProject::class)
            ->withPivot('role')
            ->withTimestamps();
    }


    protected function casts(): array
    {
        return [
            'joining_date' => 'date', //done
            'salary' => 'decimal:2', //done
            'is_active' => 'boolean', //done
        ];
    }


    protected function name(): Attribute
    {
        return Attribute::make(
            // Mutator: transforms the value before Eloquent stores it.
            set: fn(string $value) => $this->alternateCase($value),

            // Accessor: transforms the stored value when you read it.
            get: fn(string $value) => ucwords(strtolower($value)),
        );
    }

    /**
     * Converts letters to alternating uppercase and lowercase.
     */
    private function alternateCase(string $value): string
    {
        $result = '';
        $uppercase = true;

        foreach (str_split($value) as $character) {
            // str_split() splits the string into an array of characters, allowing us to iterate over each character individually. eg "Hello" becomes ['H', 'e', 'l', 'l', 'o'].
            if (ctype_alpha($character)) {
                // ctype_alpha() checks if the character is an alphabetic letter (A-Z or a-z). It returns true for letters and false for non-letter characters (like spaces, numbers, punctuation).
                $result .= $uppercase // checks the current case state (uppercase or lowercase) and appends the character in the appropriate case to the result string. //eg if $uppercase is true, it appends the uppercase version of the character; if false, it appends the lowercase version.
                    ? strtoupper($character) // strtoupper() converts the character to uppercase. //eg if $character is 'a', it becomes 'A'.
                    : strtolower($character); // strtolower() converts the character to lowercase. //eg if $character is 'A', it becomes 'a'.

                $uppercase = !$uppercase; // toggles the case state for the next letter. If it was uppercase, it becomes lowercase, and vice versa. //so if the first letter is uppercase, the next letter will be lowercase, and this alternation continues for each subsequent letter. // if first letter is lowercase, the next letter will be uppercase, and this alternation continues for each subsequent letter.
            } else {
                $result .= $character; // else, if the character is not a letter (like a space or punctuation), it is added to the result string without changing its case. //eg if $character is ' ', it remains ' ' in the result.

                // Start alternating again after a space.
                if ($character === ' ') { // checks if the character is a space. If it is, it resets the case state to uppercase for the next letter after the space. //eg if the character is ' ', it resets $uppercase to true, so the next letter after the space will be uppercase.
                    $uppercase = true;
                }
            }
        }

        return $result;
    }

    // protected function name(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn(string $value) => strtolower($value),
    //     );

    //ucwords() capitalizes the first letter of each word.
    //strtolower() converts all letters to lowercase.


    // Function | What it does | Example output |
    // |---|---|---|
    // | `strtolower()` | Converts all letters to lowercase | `"  miss elise schaefer  "` |
    // | `strtoupper()` | Converts all letters to uppercase | `"  MISS ELISE SCHAEFER  "` |
    // | `ucwords()` | Capitalizes the first letter of each word | `"  MISS Elise SCHAEFER  "` |
    // | `ucfirst()` | Capitalizes the first character of the string | `"  mISS eLISE sCHAEFER  "` |
    // | `lcfirst()` | Converts the first character to lowercase | `"  mISS eLISE sCHAEFER  "` |
    // | `trim()` | Removes whitespace from the beginning and end | `"mISS eLISE sCHAEFER"` |
    // | `ltrim()` | Removes whitespace from the beginning | `"mISS eLISE sCHAEFER  "` |
    // | `rtrim()` | Removes whitespace from the end | `"  mISS eLISE sCHAEFER"` |
    // | `strrev()` | Reverses a string | `"  REAHCEHS eSILE SSI m"` |
    // | `strlen()` | Returns the string's byte length | An integer |
    // | `str_word_count()` | Counts words in a string | An integer |
    // | `substr()` | Extracts part of a string | A substring |
    // | `str_replace()` | Replaces specified text | Depends on the replacement |
    // | `str_contains()` | Checks whether a string contains text | `true` or `false` |
    // | `str_starts_with()` | Checks whether a string starts with text | `true` or `false` |
    // | `str_ends_with()` | Checks whether a string ends with text | `true` or `false` |
    // }



    protected function annualSalary(): Attribute
    {
        return Attribute::make(
            get: fn() => (float) $this->salary * 12,
        );
    }

    protected function isHighSalary(): Attribute
    {
        return Attribute::make(
            get: fn() => (float) $this->salary > 30000,
        );
    }

    protected function daysSinceJoining(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->joining_date
                ? (int) $this->joining_date->diffInDays(now())
                : null,
        );
    }

    protected function basicInformation(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'name' => $this->name,
                'email' => $this->email,
                'salary' => $this->salary,
            ],
        );
    }
    // | Operation | Example | Possible PHP return type |
    // |---|---|---|
    // | Lowercase text | `strtolower($value)` | `string` |
    // | Uppercase text | `strtoupper($value)` | `string` |
    // | Mathematical calculation | `$this->salary * 12` | `float` or another numeric type, depending on the operation |
    // | Boolean comparison | `$this->salary > 30000` | `bool` |
    // | Date formatting | `$this->joining_date?->format('d-m-Y')` | `string` or `null` |
    // | Date difference | Date-difference calculation | `int` or another type, depending on the method and cast |
    // | Combine attributes | `$this->name . $this->email` | `string` |
    // | Return several values | `['name' => $this->name]` | `array` |
    // | Read a relationship | `$this->department?->department_name` | Usually `string` or `null` |


    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * All documents belonging to this employee.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
