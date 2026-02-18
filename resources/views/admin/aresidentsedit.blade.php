<h2>Edit Request</h2>

<form method="POST" action="{{ route('resident.update', $resident->id) }}">
    @csrf
    @method('PUT')

    <label>First Name</label><br>
    <input type="text" name="first_name" value="{{ $resident->firstname }}"><br><br>

    <label>Last Name</label><br>
    <input type="text" name="last_name" value="{{ $resident->lastname }}"><br><br>

    <label>Email Address</label><br>
    <input type="text" name="email" value="{{ $resident->email }}"><br><br>

    <label>Address</label><br>
    <input type="text" name="address" value="{{ $resident->address }}"><br><br>

    <label>Date of Birth</label><br>
    <input type="date" name="birthdate" value="{{ $resident->birthdate }}"><br><br>

    <label>Contact Number</label><br>
    <input type="text" name="contact_number" value="{{ $resident->contact }}"><br><br>

    <label>Username</label><br>
    <textarea name="username">{{ $resident->username }}</textarea><br><br>

    <button type="submit">Update</button>
</form>

<br>
<a href="{{ route('resident.view', $resident->id) }}">Cancel</a>
