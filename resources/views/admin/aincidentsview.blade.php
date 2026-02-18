<h2>Incident Report Details</h2>

<table border="1">
    <tr><th>ID</th><td>{{ $incident->incident_id }}</td></tr>
    <tr><th>Full Name</th><td>{{ $incident->full_name }}</td></tr>
    <tr><th>Address</th><td>{{ $incident->address }}</td></tr>
    <tr><th>Location</th><td>{{ $incident->location }}</td></tr>
    <tr><th>Date of Incident</th><td>{{ $incident->date_of_incident ?? 'N/A' }}</td></tr>
    <tr><th>Contact Number</th><td>{{ $incident->contact_number }}</td></tr>
    <tr><th>Type of Incident</th><td>{{ $incident->type_of_incident }}</td></tr>
    <tr><th>Description</th><td>{{ $incident->description }}</td></tr>
    <tr><th>Proof of Incident</th><td>{{ $incident->proof_of_incident }}</td></tr>
    <tr><th>Status</th><td>{{ $incident->status }}</td></tr>
</table>

<br>

<a href="{{ url()->previous() }}">⬅ Back</a>
