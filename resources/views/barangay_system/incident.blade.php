<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barangay Hulo - Incident Report</title>
</head>
<body>
    <form method="post" action="{{ route('incident.add')}}" enctype="multipart/form-data">
        @method("post")
        @csrf
        <table border=1>
            <tr>
                <th><a href="{{ route('barangay_system.index')}}">{{ __('messages.home') }}</a></th>
                <th><a href="">{{ __('messages.about') }}</a></th>
                <th><a href="{{ route('barangay_system.services') }}">{{ __('messages.services') }}</a></th>
                <th><a href= >{{ __('messages.community') }}</a></th>
                <th><a href="{{ route('barangay_system.incident') }}">{{ __('messages.report') }}</a></th>
                <th><a href= >{{ __('messages.contact') }}</a></th>
                <th><a href="{{ route('logout.res') }}">{{ __('messages.logout') }}</a></th>
            </tr>
        </table>
        <input type="hidden" name="request_type" value="indigency">
        <br>
        <h1>INCIDENT REPORT</h1>
        <br>
        <label for="full_name">Name of Complainant</label>
        <input type="text" name="full_name" placeholder="Juan Delacruz" required>
        <br>
        <label for="address">Address</label>
        <input type="text" name="address" placeholder="House No., Street, Zone" required autocomplete="street-address">
        <br>
        <label for="type_of_incident">Type of Incident</label>
        <textarea name="type_of_incident" rows="3" cols="40" placeholder="Write here..."></textarea>
        <br>
        <label for="contact_number">Contact Number</label>
        <input type="tel" id="contact_number" name="contact_number" placeholder="09xxxxxxxxx" required pattern="[0-9]{11}">
        <br>
        <label for="location">Location</label>
        <textarea name="location" rows="1" cols="40" placeholder="Write here..."></textarea>
        <br>
        <label for="date_of_incident">Date & Time of Incident</label>
        <input type="datetime-local" id="date_of_incident" name="date_of_incident" required>
        <br>
        <label for="description">Description</label>
        <textarea name="description" rows="3" cols="40" placeholder="Write here..."></textarea>
        <br>
        <label for="proof_of_incident">Upload Evidences:</label>
        <input type="file" name="proof_of_incident" accept=".jpg, .jpeg, .png" required>
        <br>
        <a href="{{ route('barangay_system.index') }}">
            <button type="button">Back</button>
        </a>
        <button type="submit">Submit</button>
    </form>
    <h1>Incident Report Status</h1>

    @if($reports->isEmpty())
        <p>No incident report found.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>Incident ID</th>
                    <th>Resident ID</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Location</th>
                    <th>Date of Incident</th>
                    <th>Contact Number</th>
                    <th>Type of Incident</th>
                    <th>Description</th>
                    <th>Proof of Incident</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $incident)
                    <tr>
                        <td>{{ $incident->incident_id }}</td>
                        <td>{{ $incident->resident_id }}</td>
                        <td>{{ $incident->full_name }}</td>
                        <td>{{ $incident->address }}</td>
                        <td>{{ $incident->location }}</td>
                        <td>{{ $incident->date_of_incident }}</td>
                        <td>{{ $incident->contact_number }}</td>
                        <td>{{ $incident->type_of_incident }}</td>
                        <td>{{ $incident->description ?? '-' }}</td>
                        <td>
                            @if($incident->proof_of_incident)
                                <a href="{{ asset($incident->proof_of_incident) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $incident->status ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
<script type="module">
  import Chatbox from 'https://cdn.jsdelivr.net/npm/@chaindesk/embeds@latest/dist/chatbox/index.js';

  const widget = await Chatbox.initBubble({
    agentId: 'cmjoevt2d04giiz0r9u2i0zcb',
    
    // optional 
    // If provided will create a contact for the user and link it to the conversation
    contact: {
      firstName: 'John',
      lastName: 'Doe',
      email: 'customer@email.com',
      phoneNumber: '+33612345644',
      userId: '42424242',
    },
    // optional
    // Override initial messages
    initialMessages: [
      'Hello Georges how are you doing today?',
      'How can I help you ?',
    ],
    // optional
    // Provided context will be appended to the Agent system prompt
    context: "The user you are talking to is John. Start by Greeting him by his name.",
  });

  // open the chat bubble
  widget.open();

  // close the chat bubble
  widget.close()

  // or 
  widget.toggle()
</script>
</html>