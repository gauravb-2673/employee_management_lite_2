<form action="{{ route('claims.index') }}" method="GET">

    <div>
        <label for="statussearch" class="mb-1.5 block text-sm font-medium text-slate-700">
            Search employees
        </label>

        <input type="text" name="statussearch" id="statussearch" value="{{ request('statussearch') }}">



        <div>
            <button type="submit">
                Search
            </button>
        </div>


        <div>
            <a href="{{ route('claims.index') }}">
                Clear Filters
            </a>
        </div>
    </div>
</form>

<table border="1">

    <th>
        <tr>
            <td>Employee Name</td>
            <td>Category</td>
            <td>Amount</td>
            <td>Expense date</td>
            <td>Description</td>
            <td>Status</td>
            <td>Reviewed by</td>
            <td>Reviewed at</td>
            <td>Rejection reason</td>
        <tr>
    </th>
    <tbody>
        @foreach ($claims as $claim)
            <tr>
                <td> {{ $claim->employee->name }}</td>
                <td>{{ $claim->category }}</td>
                <td>{{ $claim->amount }}</td>
                <td>{{ $claim->expense_date }}</td>
                <td>{{ $claim->description }}</td>
                <td> {{ $claim->status }}</td>
                <td> {{ $claim->user->name }}</td>
                <td>{{ $claim->reviewed_at }}</td>
                <td> {{ $claim->rejection_reason }}</td>
            </tr>
        @endforeach
    </tbody>



</table>
