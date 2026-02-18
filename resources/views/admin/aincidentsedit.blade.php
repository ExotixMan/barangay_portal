<h2>Edit Incident</h2>

<form method="POST" action="{{ route('incident.update', $incident->incident_id) }}">
    @csrf
    @method('PUT')

    <label>Full Name</label><br>
    <input type="text" name="full_name" value="{{ $incident->full_name }}"><br><br>

    <label>Address</label><br>
    <input type="text" name="address" value="{{ $incident->address }}"><br><br>

    <label>Location</label><br>
    <input type="text" name="location" value="{{ $incident->location }}"><br><br>

    <label>Date of Incident</label><br>
    <input type="datetime-local" name="date_of_incident" value="{{ $incident->date_of_incident }}"><br><br>

    <label>Contact Number</label><br>
    <input type="text" name="contact_number" value="{{ $incident->contact_number }}"><br><br>

    <label>Description</label><br>
    <textarea name="description">{{ $incident->description }}</textarea><br><br>

    <label>Proof of Incident</label><br>
    <input type="file" name="valid_id_path" accept=".jpg, .jpeg, .png" value="{{ $incident->proof_of_incident }}"><br><br>

    <label>Status</label><br>
    <textarea name="status">{{ $incident->status }}</textarea><br><br>

    <button type="submit">Update</button>
</form>

<br>
<a href="{{ route('incident.view', $incident->incident_id) }}">Cancel</a>
