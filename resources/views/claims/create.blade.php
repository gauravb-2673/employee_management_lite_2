<form method="POST" action="{{ route('claims.store') }}" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="employee_id">Employee</label>
        <select name="employee_id" id="employee_id">
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>
        @error('employee_id')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="category">Category</label>
        <input type="text" name="category" id="category" value="{{ old('category') }}">
        @error('category')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="amount">Amount</label>
        <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount') }}">
        @error('amount')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="expense_date">Expense Date</label>
        <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date') }}">
        @error('expense_date')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="description">Description</label>
        <textarea name="description" id="description">{{ old('description') }}</textarea>
        @error('description')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <div>
        <label for="document">Document</label>
        <input type="file" name="document" id="document">
        @error('document')
            <span>{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" onclick="return confirm('Are you sure you want to submit this claim?')">
        Submit Claim
    </button>
</form>
