<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports Dashboard</title>
</head>
<body>

<h2>Incident Reports</h2>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Location</th>
            <th>Date of Incident</th>
            <th>Contact Number</th>
            <th>Type of Incident</th>
            <th>Description</th>
            <th>Proof of Incident</th>
            <th>Status</th>
            <th>Functions</th>
            <th>Notify</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($incidents as $incident)
            <tr>
                <td>{{ $incident->incident_id }}</td>
                <td>{{ $incident->full_name }}</td>
                <td>{{ $incident->address }}</td>
                <td>{{ $incident->location }}</td>
                <td>{{ $incident->date_of_incident }}</td>
                <td>{{ $incident->contact_number }}</td>
                <td>{{ $incident->type_of_incident }}</td>
                <td>{{ $incident->description ?? 'N/A' }}</td>
                <td>
                    @if($incident->proof_of_incident)
                        <a href="{{ asset($incident->proof_of_incident) }}" target="_blank">View Proof</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $incident->status ?? 'Unknown' }}</td>
                <td>
                    <a href="{{ route('incident.view', $incident->incident_id) }}"><button type="submit">View</button></a>
                    <a href="{{ route('incident.edit', $incident->incident_id) }}"><button type='submit' name='edit'>Edit</button></a>
                    <form method="POST" action="{{ route('incident.delete', $incident->incident_id) }}"
                        style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this incident report?')">
                            Delete
                        </button>
                    </form>
                </td>
                <td>
                    {{-- EMAIL --}}
                    <form method="POST" action="{{ route('request.sendEmail') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="email" value="{{ $incident->email_address ?? '' }}">
                        <input type="hidden" name="name" value="{{ $incident->full_name }}">
                        <input type="hidden" name="message"
                            value="
                                Incident Report Update (ID: {{ $incident->incident_id }})

                                Type: {{ $incident->type_of_incident }}
                                Location: {{ $incident->location }}
                                Date: {{ \Carbon\Carbon::parse($incident->date_of_incident)->format('F d, Y') }}

                                Current Status: {{ $incident->status ?? 'Pending review' }}

                                Please coordinate with the barangay office for further assistance.
                                ">
                            <button type="submit">
                                Send Email
                            </button>
                    </form>

                    {{-- SMS --}}
                    <form method="POST" action="{{ route('request.sendSMS') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="phone"
                            value="+63{{ ltrim($incident->contact_number, '0') }}">
                        <input type="hidden" name="message"
                            value="Barangay Incident Update: Your report (ID {{ $incident->incident_id }}) is currently '{{ $incident->status ?? 'Pending' }}'.">
                        <button type="submit">
                            Send SMS
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
