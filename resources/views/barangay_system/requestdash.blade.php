<h2>Request Dashboard</h2>

<table border="1">
    @if (session()->has('error'))
        <div>
            {{session('error')}}
        </div>
    @endif
    <thead>
        <tr>
            <th>ID</th>
            <th>Request Type</th>
            <th>Full Name</th>
            <th>Complete Address</th>
            <th>Age</th>
            <th>Date of Birth</th>
            <th>Contact Number</th>
            <th>Email Address</th>
            <th>Purpose of Request</th>
            <th>Specify Others</th>
            <th>Valid ID Path</th>
            <th>Proof of Residency Path</th>
            <th>Remarks</th>
            <th>Date Submitted</th>
            <th>Document</th>
            <th>Functions</th>
            <th>Notify</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                <td>{{ $request->request_id }}</td>
                <td>{{ $request->request_type }}</td>
                <td>{{ $request->full_name }}</td>
                <td>{{ $request->complete_address }}</td>
                <td>{{ $request->age ?? 'N/A' }}</td>
                <td>{{ $request->date_of_birth ?? 'N/A' }}</td>
                <td>{{ $request->contact_number }}</td>
                <td>{{ $request->email_address ?? 'N/A' }}</td>
                <td>{{ $request->purpose_of_request ?? 'N/A' }}</td>
                <td>{{ $request->specify_others ?? 'N/A' }}</td>
                <td>
                    @if($request->valid_id_path)
                        <a href="{{ asset($request->valid_id_path) }}" target="_blank">View ID</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($request->proof_of_residency_path)
                        <a href="{{ asset($request->proof_of_residency_path) }}" target="_blank">View Proof</a>
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $request->remarks ?? 'N/A' }}</td>
                <td>{{ $request->date_submitted }}</td>
                <td>
                    <form method="GET" action="{{ route('generate.document') }}" target="_blank">
                        <input type="hidden" name="request_id" value="{{ $request->request_id }}">
                        <button type="submit" name="action" value="download">Download Word</button>
                        <button type="submit" name="action" value="print">Print PDF</button>
                    </form>   
                </td>
                <td>
                    <a href="{{ route('request.view', $request->request_id) }}"><button type="submit">View</button></a>
                    <a href="{{ route('request.edit', $request->request_id) }}"><button type='submit' name='edit'>Edit</button></a>
                    <form method="POST" action="{{ route('request.delete', $request->request_id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this request?')">
                            Delete
                        </button>
                    </form>
                </td>
                <td>
                    {{-- EMAIL --}}
                    <form method="POST" action="{{ route('request.sendEmail') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="email" value="{{ $request->email_address }}">
                        <input type="hidden" name="name" value="{{ $request->full_name }}">
                        <input type="hidden" name="message"
                            value="Your barangay request (ID: {{ $request->request_id }}) has been processed. Please check the barangay office for updates.">
                        <button type="submit">
                            Send Email
                        </button>
                    </form>

                    {{-- SMS --}}
                    <form method="POST" action="{{ route('request.sendSMS') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="phone" value="+63{{ ltrim($request->contact_number, '0') }}">
                        <input type="hidden" name="message"
                            value="Barangay update: Your request ID {{ $request->request_id }} has been processed.">
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