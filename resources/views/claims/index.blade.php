<form action="{{ route('claims.index') }}" method="GET">
    <div>
        <label for="statussearch">Search status</label>

        <input type="text" name="statussearch" id="statussearch" value="{{ request('statussearch') }}">

        <button type="submit">Search</button>

        <a href="{{ route('claims.index') }}">Clear Filters</a>
    </div>
</form>

<form action="{{ route('claims.create') }}" method="GET">
    <button type="submit">Create Claim</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Expense Date</th>
            <th>Description</th>
            <th>Status</th>
            <th>Reviewed By</th>
            <th>Reviewed At</th>
            <th>Rejection Reason</th>
            <th>Review</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($claims as $claim)
            <tr>
                <td>{{ $claim->employee?->name ?? 'Employee Not Found' }}</td>
                <td>{{ $claim->category }}</td>
                <td>{{ $claim->amount }}</td>
                <td>{{ $claim->expense_date }}</td>
                <td>{{ $claim->description }}</td>
                <td>{{ ucfirst($claim->status) }}</td>
                <td>{{ $claim->user?->name ?? 'Not Reviewed' }}</td>
                <td>{{ $claim->reviewed_at ?? 'Not Reviewed' }}</td>
                <td>{{ $claim->rejection_reason ?? '' }}</td>

                <td>
                    @if (auth()->check() && auth()->user()->hasRole('admin') && $claim->status === 'pending')
                        <a href="{{ route('claims.review', $claim->id) }}">
                            <button type="button">Review</button>
                        </a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
    {{ $claims->links('vendor.pagination.claims') }}
</div>
<style>
    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
    }

    .custom-pagination a,
    .custom-pagination span {
        padding: 8px 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }

    .custom-pagination .active {
        background-color: #2563eb;
        color: white;
    }

    .custom-pagination .disabled {
        color: gray;
    }
</style>
