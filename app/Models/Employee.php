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
            get: fn(string $value) => strtolower($value),
        );

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
    }

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
}
