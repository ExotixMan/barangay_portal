<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residents Dashboard</title>
</head>
<body>

<h2>Residents Dashboard</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Birthdate</th>
            <th>Contact</th>
            <th>Username</th>
            <th>Password</th>
            <th>Functions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($residents as $resident)
            <tr>
                <td>{{ $resident->id }}</td>
                <td>{{ $resident->firstname }}</td>
                <td>{{ $resident->lastname }}</td>
                <td>{{ $resident->email }}</td>
                <td>{{ $resident->address }}</td>
                <td>{{ $resident->birthdate }}</td>
                <td>{{ $resident->contact }}</td>
                <td>{{ $resident->username }}</td>
                <td>{{ $resident->password }}</td>
                <td>
                    <a href="{{ route('resident.view', $resident->id) }}"><button type="submit">View</button></a>
                    <a href="{{ route('resident.edit', $resident->id) }}"><button type='submit' name='edit'>Edit</button></a>
                    <form method="POST" action="{{ route('resident.delete', $resident->id) }}"
                        style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this resident data?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
