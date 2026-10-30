javascript - 


const input = document.getElementById('fullname');
const stateSelect = document.getElementById('state_id');

JavaScript now has a reference to the HTML element.

You can manipulate it:
input.value
input.disabled
input.required
input.value = "John"
stateSelect.value  this gives the value which is selected.

Events -
click
change
submit
input
keyup
mouseover


stateSelect.addEventListener('change', function () {
    console.log(this.value);
});

User changes dropdown
        ↓
browser generates "change" event
        ↓
addEventListener catches it
        ↓
function executes
        ↓
this.value gives selected value

============================================
HTTP response vs JSON

const response = await fetch(url);

gives you a Response object.

response
 ├── status
 ├── ok
 ├── headers
 └── body
 
 
 =====================================================
 
 
 HTML FORM VALIDATION CHEAT SHEET
================================

HTML validation happens in the browser before the form is submitted.
It does NOT replace Laravel validation.

--------------------------------------------------
1. required
--------------------------------------------------

Makes a field mandatory.

<input type="text" name="title" required>

Works with:
- input
- select
- textarea

Example:

<input type="email" name="email" required>


--------------------------------------------------
2. minlength
--------------------------------------------------

Requires a minimum number of characters.

<input
    type="text"
    name="username"
    minlength="3"
>

"ab"      -> invalid
"abc"     -> valid


--------------------------------------------------
3. maxlength
--------------------------------------------------

Limits the maximum number of characters.

<input
    type="text"
    name="title"
    maxlength="150"
>

Example:

<input
    type="text"
    name="title"
    required
    maxlength="150"
>


--------------------------------------------------
4. min
--------------------------------------------------

Sets the minimum numeric/date value.

Example:

<input
    type="number"
    name="stock"
    min="0"
>

-1 -> invalid
0  -> valid
5  -> valid


--------------------------------------------------
5. max
--------------------------------------------------

Sets the maximum numeric/date value.

Example:

<input
    type="number"
    name="age"
    max="100"
>

101 -> invalid
100 -> valid
50  -> valid


--------------------------------------------------
6. step
--------------------------------------------------

Controls allowed increments for numeric values.

For money:

<input
    type="number"
    name="price"
    step="0.01"
>

Examples:

10       -> valid
10.25    -> valid
10.50    -> valid
10.99    -> valid

For integers:

<input
    type="number"
    name="stock"
    step="1"
>

For increments of 5:

<input
    type="number"
    name="quantity"
    step="5"
>

Possible values:

0
5
10
15
20


--------------------------------------------------
7. type="email"
--------------------------------------------------

Browser checks for an email-like format.

<input
    type="email"
    name="email"
    required
>

Example:

john@example.com -> valid
john@example    -> invalid


--------------------------------------------------
8. type="url"
--------------------------------------------------

Used for URLs.

<input
    type="url"
    name="website"
>

Example:

https://example.com


--------------------------------------------------
9. pattern
--------------------------------------------------

Allows you to specify a regular expression.

Example:
Exactly 10 digits:

<input
    type="text"
    name="phone"
    pattern="[0-9]{10}"
>

Valid:

9876543210

Invalid:

987654321
98765432101
abc1234567


You can provide a browser hint/message using title:

<input
    type="text"
    name="phone"
    pattern="[0-9]{10}"
    title="Phone number must contain exactly 10 digits"
>


--------------------------------------------------
10. required + minlength + maxlength
--------------------------------------------------

Multiple HTML rules can be used together.

<input
    type="text"
    name="username"
    required
    minlength="3"
    maxlength="20"
>

Meaning:

- required
- minimum 3 characters
- maximum 20 characters


--------------------------------------------------
11. required with select
--------------------------------------------------

<select
    name="status"
    required
>
    <option value="">-- Select status --</option>
    <option value="available">Available</option>
    <option value="unavailable">Unavailable</option>
</select>

Because the first option has value="",
the user must select another option.


--------------------------------------------------
12. required with textarea
--------------------------------------------------

<textarea
    name="description"
    required
></textarea>

The user must enter something.

If the field is optional, do NOT use required:

<textarea
    name="description"
></textarea>


--------------------------------------------------
13. type="date"
--------------------------------------------------

Used for dates.

<input
    type="date"
    name="birth_date"
    required
>


--------------------------------------------------
14. date + min + max
--------------------------------------------------

Restricts a date range.

<input
    type="date"
    name="start_date"
    min="2026-01-01"
    max="2026-12-31"
>


--------------------------------------------------
15. type="number"
--------------------------------------------------

Used for numeric values.

<input
    type="number"
    name="stock"
>

Can be combined with:

<input
    type="number"
    name="stock"
    required
    min="0"
    max="1000"
    step="1"
>


--------------------------------------------------
16. type="time"
--------------------------------------------------

Used for time values.

<input
    type="time"
    name="opening_time"
>

Can also use min/max:

<input
    type="time"
    name="opening_time"
    min="09:00"
    max="18:00"
>


--------------------------------------------------
17. type="file" + accept
--------------------------------------------------

Suggests which file types can be selected.

Images:

<input
    type="file"
    name="book_cover"
    accept="image/*"
>

PDF:

<input
    type="file"
    name="document"
    accept=".pdf"
>

Multiple file extensions:

<input
    type="file"
    name="document"
    accept=".pdf,.doc,.docx"
>

IMPORTANT:
accept is NOT a security mechanism.
Laravel must still validate uploaded files.


--------------------------------------------------
18. checkbox + required
--------------------------------------------------

Useful for terms and conditions.

<input
    type="checkbox"
    name="terms"
    required
>

<label>I accept the terms</label>


--------------------------------------------------
19. radio + required
--------------------------------------------------

Used when the user must select one option.

<input
    type="radio"
    name="gender"
    value="male"
    required
>

<input
    type="radio"
    name="gender"
    value="female"
>


--------------------------------------------------
20. novalidate
--------------------------------------------------

Disables native browser validation.

<form novalidate>

Usually DON'T use this when you want
HTML browser validation.


==================================================
YOUR BOOK FORM
==================================================

<form method="POST" action="{{ route('books.store') }}">

    @csrf

    <label for="title">Title:</label>

    <input
        type="text"
        name="title"
        id="title"
        required
        maxlength="150"
    >

    <br><br>


    <label for="author">Author:</label>

    <input
        type="text"
        name="author"
        id="author"
        required
        maxlength="100"
    >

    <br><br>


    <label for="isbn">ISBN:</label>

    <input
        type="text"
        name="isbn"
        id="isbn"
        required
        maxlength="20"
    >

    <br><br>


    <label for="price">Price:</label>

    <input
        type="number"
        name="price"
        id="price"
        required
        min="0"
        step="0.01"
    >

    <br><br>


    <label for="published_year">Published year:</label>

    <input
        type="number"
        name="published_year"
        id="published_year"
        required
        min="1000"
        max="9999"
        step="1"
    >

    <br><br>


    <label for="description">Description:</label>

    <textarea
        name="description"
        id="description"
    ></textarea>

    <br><br>


    <label for="status">Status:</label>

    <select
        name="status"
        id="status"
        required
    >
        <option value="">-- Select status --</option>
        <option value="available">Available</option>
        <option value="unavailable">Unavailable</option>
    </select>

    <br><br>


    <label for="stock">Stock:</label>

    <input
        type="number"
        name="stock"
        id="stock"
        required
        min="0"
        step="1"
    >

    <br><br>


    <button type="submit">
        Save Book
    </button>

</form>


==================================================
HTML VALIDATION VS LARAVEL VALIDATION
==================================================

HTML:

required
maxlength="150"
min="0"
max="9999"
step="0.01"
type="email"
pattern="..."
accept="..."


Laravel:

required
max:150
min:0
between:1000,9999
decimal:2
email
regex:...
mimes:...


IMPORTANT:

HTML validation
    ↓
Browser-side validation
    ↓
Improves user experience
    ↓
Can prevent normal browser submission


Laravel validation
    ↓
Server-side validation
    ↓
Protects the application
    ↓
Must ALWAYS be present


==================================================
IMPORTANT LIMITATION
==================================================

HTML cannot check database-dependent rules.

For example:

'unique:books,isbn'

HTML cannot know whether an ISBN
already exists in your MySQL database.

Laravel must perform that check.

The correct architecture is:

Browser
   ↓
HTML validation
   ↓
POST request
   ↓
Laravel FormRequest
   ↓
Laravel validation
   ↓
Database checks
   ↓
Controller
   ↓
Book::create()
   ↓
Database


==================================================
YOUR LARAVEL RULES
==================================================

'title' => 'required|string|max:150',

'author' => 'required|string|max:100',

'isbn' => 'required|string|max:20|unique:books,isbn',

'price' => 'required|decimal:2|min:0',

'published_year' => 'required|integer|between:1000,9999',

'description' => 'nullable|string',

'status' => 'required|string|in:available,unavailable',

'stock' => 'required|integer|min:0',

HTML FORMS + INPUTS COMPLETE CHEAT SHEET
========================================


==================================================
1. BASIC HTML DOCUMENT
==================================================

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Page</title>
</head>

<body>

    <h1>Hello</h1>

</body>

</html>


==================================================
2. FORM BASIC STRUCTURE
==================================================

<form
    method="POST"
    action="/users"
>

    <!-- form fields -->

    <button type="submit">
        Submit
    </button>

</form>


method commonly used:

GET
POST


GET:
- Used to retrieve/send query data
- Data appears in URL
- Should not be used for passwords or sensitive form data

Example:

<form method="GET" action="/search">

    <input type="text" name="query">

    <button type="submit">
        Search
    </button>

</form>


POST:
- Sends data in the request body
- Commonly used for create/store operations
- Does not put normal form fields in the URL

Example:

<form method="POST" action="/users">

    <input type="text" name="name">

    <button type="submit">
        Save
    </button>

</form>


==================================================
3. FORM action
==================================================

<form action="/users">

</form>

The action specifies where the form is submitted.


Laravel Blade:

<form
    method="POST"
    action="{{ route('books.store') }}"
>

</form>


==================================================
4. LABEL
==================================================

<label for="title">
    Title
</label>

<input
    type="text"
    id="title"
    name="title"
>


IMPORTANT:

label's "for"
must match input's "id".

Example:

<label for="email">
    Email
</label>

<input
    type="email"
    id="email"
    name="email"
>


==================================================
5. INPUT BASIC STRUCTURE
==================================================

<input
    type="text"
    name="username"
    id="username"
>


Important attributes:

type
name
id
value
placeholder
required
disabled
readonly
autocomplete
class
style


==================================================
6. type="text"
==================================================

Used for normal text.

<input
    type="text"
    name="title"
    id="title"
>


Example:

<input
    type="text"
    name="username"
    placeholder="Enter username"
>


==================================================
7. type="password"
==================================================

Used for passwords.

<input
    type="password"
    name="password"
    id="password"
>


Browser hides the characters.

Example:

<input
    type="password"
    name="password"
    required
>


IMPORTANT:

type="password"
does NOT encrypt the password.

HTTPS and server-side security are still required.


==================================================
8. type="email"
==================================================

Used for email addresses.

<input
    type="email"
    name="email"
    id="email"
>


Can use:

required

Example:

<input
    type="email"
    name="email"
    required
>


==================================================
9. type="number"
==================================================

Used for numbers.

<input
    type="number"
    name="age"
>


With validation:

<input
    type="number"
    name="age"
    min="18"
    max="100"
    step="1"
>


For price:

<input
    type="number"
    name="price"
    min="0"
    step="0.01"
>


==================================================
10. type="tel"
==================================================

Used for telephone numbers.

<input
    type="tel"
    name="phone"
>


Can use pattern:

<input
    type="tel"
    name="phone"
    pattern="[0-9]{10}"
>


==================================================
11. type="url"
==================================================

Used for URLs.

<input
    type="url"
    name="website"
>


Example:

https://example.com


==================================================
12. type="search"
==================================================

Used for search fields.

<input
    type="search"
    name="query"
    placeholder="Search..."
>


==================================================
13. type="date"
==================================================

Used for dates.

<input
    type="date"
    name="birth_date"
>


With range:

<input
    type="date"
    name="date"
    min="2026-01-01"
    max="2026-12-31"
>


==================================================
14. type="datetime-local"
==================================================

Used for date + time.

<input
    type="datetime-local"
    name="appointment"
>


==================================================
15. type="time"
==================================================

Used for time.

<input
    type="time"
    name="start_time"
>


Example:

<input
    type="time"
    name="start_time"
    min="09:00"
    max="18:00"
>


==================================================
16. type="month"
==================================================

Used for month/year.

<input
    type="month"
    name="billing_month"
>


Example:

2026-09


==================================================
17. type="week"
==================================================

Used for week/year.

<input
    type="week"
    name="week"
>


==================================================
18. type="color"
==================================================

Color picker.

<input
    type="color"
    name="color"
>


==================================================
19. type="hidden"
==================================================

Stores a value that isn't displayed to the user.

<input
    type="hidden"
    name="user_id"
    value="10"
>


IMPORTANT:

Hidden does NOT mean secure.

A user can modify hidden input values.

Never trust hidden fields for authorization/security.


==================================================
20. type="checkbox"
==================================================

Used for yes/no or multiple selections.

<input
    type="checkbox"
    name="terms"
    value="yes"
>

<label for="terms">
    Accept terms
</label>


With id:

<input
    type="checkbox"
    id="terms"
    name="terms"
    value="yes"
>

<label for="terms">
    Accept terms
</label>


==================================================
21. MULTIPLE CHECKBOXES
==================================================

<input
    type="checkbox"
    name="skills[]"
    value="php"
>

<label>PHP</label>


<input
    type="checkbox"
    name="skills[]"
    value="laravel"
>

<label>Laravel</label>


<input
    type="checkbox"
    name="skills[]"
    value="mysql"
>

<label>MySQL</label>


The [] tells the server that
multiple values can be submitted.


Possible submitted data:

skills = [
    "php",
    "laravel",
    "mysql"
]


==================================================
22. type="radio"
==================================================

Used when only ONE option should be selected.

<input
    type="radio"
    id="male"
    name="gender"
    value="male"
>

<label for="male">
    Male
</label>


<input
    type="radio"
    id="female"
    name="gender"
    value="female"
>

<label for="female">
    Female
</label>


IMPORTANT:

Radio buttons must have the SAME name
to behave as one group.

Correct:

name="gender"
name="gender"


Incorrect:

name="male"
name="female"


==================================================
23. required RADIO
==================================================

<input
    type="radio"
    name="gender"
    value="male"
    required
>

<input
    type="radio"
    name="gender"
    value="female"
>


One option must be selected.


==================================================
24. type="file"
==================================================

Used for file uploads.

<input
    type="file"
    name="document"
>


IMPORTANT:

For file uploads, form needs:

enctype="multipart/form-data"


Example:

<form
    method="POST"
    action="/upload"
    enctype="multipart/form-data"
>

    <input
        type="file"
        name="document"
    >

    <button type="submit">
        Upload
    </button>

</form>


==================================================
25. file accept
==================================================

Images:

<input
    type="file"
    name="image"
    accept="image/*"
>


PDF:

<input
    type="file"
    name="document"
    accept=".pdf"
>


Multiple:

<input
    type="file"
    name="document"
    accept=".pdf,.doc,.docx"
>


IMPORTANT:

accept is NOT security validation.

Server must validate uploaded files.


==================================================
26. multiple FILES
==================================================

<input
    type="file"
    name="documents[]"
    multiple
>


Allows multiple files.


==================================================
27. textarea
==================================================

Used for multi-line text.

<textarea
    name="description"
    id="description"
></textarea>


With rows/columns:

<textarea
    name="description"
    rows="5"
    cols="50"
></textarea>


With placeholder:

<textarea
    name="description"
    placeholder="Enter description"
></textarea>


With validation:

<textarea
    name="description"
    required
    minlength="10"
    maxlength="500"
></textarea>


IMPORTANT:

Textarea value goes BETWEEN
opening and closing tags.

Correct:

<textarea name="description">Hello</textarea>


Not:

<textarea
    name="description"
    value="Hello"
></textarea>


==================================================
28. select
==================================================

Used for dropdowns.

<select name="status">

    <option value="available">
        Available
    </option>

    <option value="unavailable">
        Unavailable
    </option>

</select>


==================================================
29. SELECT + required
==================================================

<select
    name="status"
    required
>

    <option value="">
        -- Select status --
    </option>

    <option value="available">
        Available
    </option>

    <option value="unavailable">
        Unavailable
    </option>

</select>


The empty option forces the user
to select an actual value.


==================================================
30. SELECT selected
==================================================

<select name="status">

    <option value="available" selected>
        Available
    </option>

    <option value="unavailable">
        Unavailable
    </option>

</select>


"available" is selected by default.


==================================================
31. SELECT multiple
==================================================

<select
    name="roles[]"
    multiple
>

    <option value="admin">
        Admin
    </option>

    <option value="manager">
        Manager
    </option>

    <option value="user">
        User
    </option>

</select>


Multiple options can be selected.


==================================================
32. optgroup
==================================================

Groups options.

<select name="country">

    <optgroup label="Asia">

        <option value="india">
            India
        </option>

        <option value="japan">
            Japan
        </option>

    </optgroup>


    <optgroup label="Europe">

        <option value="uk">
            United Kingdom
        </option>

        <option value="france">
            France
        </option>

    </optgroup>

</select>


==================================================
33. button
==================================================

<button>
    Click
</button>


Button types:

type="submit"
type="button"
type="reset"


==================================================
34. SUBMIT BUTTON
==================================================

<button type="submit">
    Save
</button>


Submits the form.


==================================================
35. NORMAL BUTTON
==================================================

<button type="button">
    Click
</button>


Does NOT submit the form.

Useful for JavaScript interactions.


==================================================
36. RESET BUTTON
==================================================

<button type="reset">
    Reset
</button>


Resets form controls to their initial values.


==================================================
37. input submit
==================================================

<input
    type="submit"
    value="Save"
>


Equivalent purpose to:

<button type="submit">
    Save
</button>


Usually <button> gives you more flexibility.


==================================================
38. input reset
==================================================

<input
    type="reset"
    value="Reset"
>


==================================================
39. input button
==================================================

<input
    type="button"
    value="Click"
>


Doesn't submit the form.


==================================================
40. placeholder
==================================================

Shows a hint inside the field.

<input
    type="text"
    name="username"
    placeholder="Enter username"
>


IMPORTANT:

placeholder is NOT a replacement for label.


Better:

<label for="username">
    Username
</label>

<input
    type="text"
    id="username"
    name="username"
    placeholder="Enter username"
>


==================================================
41. value
==================================================

Sets the submitted/default value.

<input
    type="text"
    name="name"
    value="John"
>


If submitted:

name = John


==================================================
42. disabled
==================================================

Disables the field.

<input
    type="text"
    name="username"
    disabled
>


IMPORTANT:

Disabled form fields are generally NOT submitted.


==================================================
43. readonly
==================================================

User cannot edit the field.

<input
    type="text"
    name="username"
    value="John"
    readonly
>


IMPORTANT:

Readonly values CAN be submitted.

Difference:

disabled
-> cannot edit
-> generally not submitted

readonly
-> cannot edit
-> submitted


==================================================
44. autocomplete
==================================================

Controls browser autocomplete behavior.

<input
    type="email"
    name="email"
    autocomplete="email"
>


Common values:

autocomplete="name"
autocomplete="email"
autocomplete="username"
autocomplete="current-password"
autocomplete="new-password"
autocomplete="street-address"
autocomplete="tel"


==================================================
45. autofocus
==================================================

Automatically focuses the field when page loads.

<input
    type="text"
    name="username"
    autofocus
>


Use carefully.
Generally only one field should have autofocus.


==================================================
46. maxlength
==================================================

Maximum number of characters.

<input
    type="text"
    maxlength="150"
>


==================================================
47. minlength
==================================================

Minimum number of characters.

<input
    type="text"
    minlength="3"
>


==================================================
48. min
==================================================

Minimum value.

<input
    type="number"
    min="0"
>


==================================================
49. max
==================================================

Maximum value.

<input
    type="number"
    max="100"
>


==================================================
50. step
==================================================

Allowed numeric increment.

<input
    type="number"
    step="0.01"
>


==================================================
51. pattern
==================================================

Regular expression validation.

<input
    type="text"
    pattern="[0-9]{10}"
>


Exactly 10 digits.


==================================================
52. required
==================================================

Field must have a value.

<input
    type="text"
    required
>


==================================================
53. form novalidate
==================================================

Disables browser's native validation.

<form novalidate>

</form>


Usually avoid this if you want HTML validation.


==================================================
54. form target
==================================================

Controls where the response opens.

<form
    action="/users"
    target="_blank"
>

</form>


Common:

target="_self"
target="_blank"


==================================================
55. form autocomplete
==================================================

Controls autocomplete for the whole form.

<form autocomplete="on">

</form>

or:

<form autocomplete="off">

</form>


==================================================
56. FIELDSET
==================================================

Groups related form fields.

<fieldset>

    <legend>
        Personal Information
    </legend>

    <label for="name">
        Name
    </label>

    <input
        type="text"
        id="name"
        name="name"
    >

</fieldset>


==================================================
57. LEGEND
==================================================

Provides a title for a fieldset.

<fieldset>

    <legend>
        Account Information
    </legend>

</fieldset>


==================================================
58. disabled FIELDSET
==================================================

Disables all controls inside it.

<fieldset disabled>

    <input
        type="text"
        name="name"
    >

    <select name="country">

        <option value="india">
            India
        </option>

    </select>

</fieldset>


==================================================
59. DATALIST
==================================================

Provides suggestions for an input.

<input
    list="languages"
    name="language"
>


<datalist id="languages">

    <option value="PHP">
    <option value="JavaScript">
    <option value="Python">
    <option value="Java">

</datalist>


The user can type their own value too.

Unlike select, datalist does not force
the user to choose one of the suggestions.


==================================================
60. FORM VALIDATION COMBINATION
==================================================

You can combine validation attributes.

Example:

<input
    type="text"
    name="username"
    required
    minlength="3"
    maxlength="20"
    pattern="[A-Za-z0-9]+"
>


Meaning:

required
AND
minimum 3 characters
AND
maximum 20 characters
AND
only letters/numbers according to pattern.


==================================================
61. PRICE INPUT
==================================================

<input
    type="number"
    name="price"
    required
    min="0"
    step="0.01"
>


==================================================
62. INTEGER INPUT
==================================================

<input
    type="number"
    name="stock"
    required
    min="0"
    step="1"
>


==================================================
63. EMAIL INPUT
==================================================

<input
    type="email"
    name="email"
    required
    autocomplete="email"
>


==================================================
64. PASSWORD INPUT
==================================================

<input
    type="password"
    name="password"
    required
    minlength="8"
    autocomplete="new-password"
>


==================================================
65. PASSWORD CONFIRMATION
==================================================

<input
    type="password"
    name="password"
    required
    minlength="8"
    autocomplete="new-password"
>


<input
    type="password"
    name="password_confirmation"
    required
    minlength="8"
    autocomplete="new-password"
>


IMPORTANT:

HTML cannot verify that the two passwords
are equal.

Laravel should validate this:

'password' => 'required|confirmed'


==================================================
66. PHONE INPUT
==================================================

<input
    type="tel"
    name="phone"
    required
    pattern="[0-9]{10}"
>


==================================================
67. SEARCH FORM
==================================================

<form
    method="GET"
    action="/search"
>

    <label for="query">
        Search
    </label>

    <input
        type="search"
        id="query"
        name="query"
        placeholder="Search..."
    >

    <button type="submit">
        Search
    </button>

</form>


==================================================
68. LOGIN FORM
==================================================

<form
    method="POST"
    action="/login"
>

    <label for="email">
        Email
    </label>

    <input
        type="email"
        id="email"
        name="email"
        required
        autocomplete="email"
    >


    <label for="password">
        Password
    </label>

    <input
        type="password"
        id="password"
        name="password"
        required
        autocomplete="current-password"
    >


    <button type="submit">
        Login
    </button>

</form>


==================================================
69. FILE UPLOAD FORM
==================================================

<form
    method="POST"
    action="/upload"
    enctype="multipart/form-data"
>

    <input
        type="file"
        name="document"
        accept=".pdf"
        required
    >

    <button type="submit">
        Upload
    </button>

</form>


==================================================
70. LARAVEL FORM + CSRF
==================================================

In Laravel:

<form
    method="POST"
    action="{{ route('books.store') }}"
>

    @csrf

    <!-- fields -->

    <button type="submit">
        Save
    </button>

</form>


@csrf generates a CSRF token.

For POST/PUT/PATCH/DELETE web forms,
Laravel uses CSRF protection.


==================================================
71. LARAVEL PUT/PATCH FORM
==================================================

HTML forms natively support:

GET
POST


They do not directly support:

PUT
PATCH
DELETE


Laravel provides method spoofing.

Example:

<form
    method="POST"
    action="{{ route('books.update', $book) }}"
>

    @csrf

    @method('PUT')

    <!-- fields -->

    <button type="submit">
        Update
    </button>

</form>


==================================================
72. LARAVEL DELETE FORM
==================================================

<form
    method="POST"
    action="{{ route('books.destroy', $book) }}"
>

    @csrf

    @method('DELETE')

    <button type="submit">
        Delete
    </button>

</form>


==================================================
73. LARAVEL OLD INPUT
==================================================

When Laravel redirects back after validation:

<input
    type="text"
    name="title"
    value="{{ old('title') }}"
>


If there was a previous value,
Laravel puts it back into the input.


With default value:

value="{{ old('title', $book->title) }}"


Useful for edit forms.


==================================================
74. LARAVEL VALIDATION ERRORS
==================================================

Display all errors:

@if ($errors->any())

    <ul>

        @foreach ($errors->all() as $error)

            <li>
                {{ $error }}
            </li>

        @endforeach

    </ul>

@endif


Display one field's error:

@error('title')

    <div>
        {{ $message }}
    </div>

@enderror


==================================================
75. LARAVEL CHECKED CHECKBOX
==================================================

<input
    type="checkbox"
    name="active"
    value="1"
    @checked(old('active', $user->active))
>


==================================================
76. LARAVEL SELECTED OPTION
==================================================

<option
    value="available"
    @selected(old('status', $book->status) === 'available')
>
    Available
</option>


==================================================
77. COMMON INPUT TYPES
==================================================

text
password
email
number
tel
url
search
date
datetime-local
month
week
time
color
hidden
checkbox
radio
file
range
submit
reset
button
image


==================================================
78. RANGE INPUT
==================================================

Slider.

<input
    type="range"
    name="volume"
    min="0"
    max="100"
    step="1"
>


==================================================
79. IMAGE BUTTON
==================================================

<input
    type="image"
    src="/images/submit.png"
    alt="Submit"
>


Usually <button type="submit"> is easier.


==================================================
80. IMPORTANT VOID ELEMENTS
==================================================

These elements do NOT need closing tags:

<input>
<br>
<hr>
<img>
<meta>
<link>
<source>
<area>
<base>
<col>
<embed>
<param>
<track>
<wbr>


IMPORTANT:

Correct:

<input type="text">


NOT:

<input type="text"></input>


Your Book form currently has:

<input ...> </input>

Remove the </input>.

Correct:

<input
    type="text"
    name="title"
>


==================================================
81. LABEL + INPUT BEST PRACTICE
==================================================

<label for="title">
    Title
</label>

<input
    type="text"
    id="title"
    name="title"
    required
>


Three important things:

id
    connects the label to the input

name
    determines the submitted field name

value
    determines the submitted value


==================================================
82. HTML VALIDATION VS LARAVEL
==================================================

HTML:

required
minlength
maxlength
min
max
step
pattern
type="email"
type="url"
type="number"
accept


Laravel:

required
string
integer
numeric
decimal
min
max
between
email
url
regex
in
unique
exists
confirmed
mimes


HTML validation:

Browser-side
↓
Good user experience
↓
Can stop normal browser submission


Laravel validation:

Server-side
↓
Security boundary
↓
Protects application
↓
Must always be used


==================================================
83. IMPORTANT SECURITY RULE
==================================================

NEVER trust HTML validation.

A user can:

- disable JavaScript
- bypass browser validation
- modify HTML
- send requests manually
- use Postman/cURL
- directly call your Laravel route


Therefore:

HTML validation
    = user experience


Laravel validation
    = application protection


==================================================
84. COMPLETE BOOK FORM EXAMPLE
==================================================

<form
    method="POST"
    action="{{ route('books.store') }}"
>

    @csrf


    <label for="title">
        Title:
    </label>

    <input
        type="text"
        name="title"
        id="title"
        required
        maxlength="150"
    >

    <br><br>


    <label for="author">
        Author:
    </label>

    <input
        type="text"
        name="author"
        id="author"
        required
        maxlength="100"
    >

    <br><br>


    <label for="isbn">
        ISBN:
    </label>

    <input
        type="text"
        name="isbn"
        id="isbn"
        required
        maxlength="20"
    >

    <br><br>


    <label for="price">
        Price:
    </label>

    <input
        type="number"
        name="price"
        id="price"
        required
        min="0"
        step="0.01"
    >

    <br><br>


    <label for="published_year">
        Published Year:
    </label>

    <input
        type="number"
        name="published_year"
        id="published_year"
        required
        min="1000"
        max="9999"
        step="1"
    >

    <br><br>


    <label for="description">
        Description:
    </label>

    <textarea
        name="description"
        id="description"
    ></textarea>

    <br><br>


    <label for="status">
        Status:
    </label>

    <select
        name="status"
        id="status"
        required
    >

        <option value="">
            -- Select status --
        </option>

        <option value="available">
            Available
        </option>

        <option value="unavailable">
            Unavailable
        </option>

    </select>

    <br><br>


    <label for="stock">
        Stock:
    </label>

    <input
        type="number"
        name="stock"
        id="stock"
        required
        min="0"
        step="1"
    >

    <br><br>


    <button type="submit">
        Save Book
    </button>

</form>


==================================================
85. THE MOST IMPORTANT CONCEPT
==================================================

For an HTML form:

<form>
    ↓
input/select/textarea
    ↓
name attribute
    ↓
browser creates form data
    ↓
HTTP request
    ↓
Laravel route
    ↓
FormRequest validation
    ↓
Controller
    ↓
Model
    ↓
Database


Remember:

id
    = identifies element in HTML

for
    = connects label to id

name
    = field name sent to server

value
    = value sent to server

type
    = tells browser what kind of input it is

required
    = browser requires a value

placeholder
    = hint for the user

disabled
    = user cannot edit AND value is generally not submitted

readonly
    = user cannot edit BUT value can be submitted

maxlength
    = maximum characters

minlength
    = minimum characters

min
    = minimum numeric/date value

max
    = maximum numeric/date value

step
    = allowed numeric increment

pattern
    = regular-expression constraint

accept
    = suggested file types

multiple
    = allows multiple selections/files
 
 

