<h2>Request Details</h2>

<table border="1">
    <tr><th>ID</th><td>{{ $request->request_id }}</td></tr>
    <tr><th>Request Type</th><td>{{ $request->request_type }}</td></tr>
    <tr><th>Full Name</th><td>{{ $request->full_name }}</td></tr>
    <tr><th>Complete Address</th><td>{{ $request->complete_address }}</td></tr>
    <tr><th>Age</th><td>{{ $request->age ?? 'N/A' }}</td></tr>
    <tr><th>Date of Birth</th><td>{{ $request->date_of_birth ?? 'N/A' }}</td></tr>
    <tr><th>Contact Number</th><td>{{ $request->contact_number }}</td></tr>
    <tr><th>Email</th><td>{{ $request->email_address ?? 'N/A' }}</td></tr>
    <tr><th>Purpose</th><td>{{ $request->purpose_of_request ?? 'N/A' }}</td></tr>
    <tr><th>Remarks</th><td>{{ $request->remarks ?? 'N/A' }}</td></tr>
    <tr><th>Date Submitted</th><td>{{ $request->date_submitted }}</td></tr>

    <tr>
        <th>Valid ID</th>
        <td>
            @if($request->valid_id_path)
                <a href="{{ asset($request->valid_id_path) }}" target="_blank">View ID</a>
            @else
                N/A
            @endif
        </td>
    </tr>

    <tr>
        <th>Proof of Residency</th>
        <td>
            @if($request->proof_of_residency_path)
                <a href="{{ asset($request->proof_of_residency_path) }}" target="_blank">View Proof</a>
            @else
                N/A
            @endif
        </td>
    </tr>
</table>

<br>

<a href="{{ url()->previous() }}">⬅ Back</a>
