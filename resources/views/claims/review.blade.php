<table border="1">
    <thead>

    </thead>

    <tbody>
        <h2>Review Expense Claim</h2>

        <table border="1">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Expense Date</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Reviewed By</th>
                    <th>Reviewed At</th>
                    <th>Rejection Reason</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{ $claim->category }}</td>
                    <td>{{ $claim->amount }}</td>
                    <td>{{ $claim->expense_date }}</td>
                    <td>{{ $claim->description }}</td>
                    <td>{{ ucfirst($claim->status) }}</td>
                    <td>{{ $claim->reviewedBy?->name ?? 'Not Reviewed' }}</td>
                    <td>{{ $claim->reviewed_at ?? 'Not Reviewed' }}</td>
                    <td>{{ $claim->rejection_reason ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        @if (auth()->user()->hasRole('admin') && $claim->status === 'pending')
            <h3>Approve Claim</h3>

            <form action="{{ route('claims.approve', $claim->id) }}" method="POST">
                @csrf
                <button type="submit">Approve</button>
            </form>

            <h3>Reject Claim</h3>

            <form action="{{ route('claims.reject', $claim->id) }}" method="POST">
                @csrf

                <label for="rejection_reason">Rejection Reason</label>

                <textarea name="rejection_reason" id="rejection_reason" required></textarea>

                <button type="submit">Reject</button>
            </form>
        @endif

        <a href="{{ route('claims.index') }}">Back to Claims</a>
    </tbody>
</table>
