<h2>Edit Request</h2>

<form method="POST" action="{{ route('request.update', $request->request_id) }}">
    @csrf
    @method('PUT')

    <label>Full Name</label><br>
    <input type="text" name="full_name" value="{{ $request->full_name }}"><br><br>

    <label>Complete Address</label><br>
    <input type="text" name="complete_address" value="{{ $request->complete_address }}"><br><br>

    <label>Contact Number</label><br>
    <input type="text" name="contact_number" value="{{ $request->contact_number }}"><br><br>

    <label>Remarks</label><br>
    <textarea name="remarks">{{ $request->remarks }}</textarea><br><br>

    <button type="submit">Update</button>
</form>

<br>
<a href="{{ route('request.view', $request->request_id) }}">Cancel</a>
