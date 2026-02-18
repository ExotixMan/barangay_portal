<h2>Residents Details</h2>

<table border="1">
    <tr><th>ID</th><td>{{ $resident->id }}</td></tr>
    <tr><th>First Name</th><td>{{ $resident->firstname }}</td></tr>
    <tr><th>Last Name</th><td>{{ $resident->lastname }}</td></tr>
    <tr><th>Email</th><td>{{ $resident->email ?? 'N/A' }}</td></tr>
    <tr><th>Address</th><td>{{ $resident->address }}</td></tr>
    <tr><th>Date of Birth</th><td>{{ $resident->birthdate ?? 'N/A' }}</td></tr>
    <tr><th>Contact Number</th><td>{{ $resident->contact }}</td></tr>
    <tr><th>Username</th><td>{{ $resident->username }}</td></tr>
</table>

<br>

<a href="{{ url()->previous() }}">⬅ Back</a>
