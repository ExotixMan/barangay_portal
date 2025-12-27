<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barangay Hulo - First-Time Job Seeker Request</title>
</head>
<body>
    <form method="post" action="{{ route('job_seeker.req')}}" enctype="multipart/form-data">
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
        <input type="hidden" name="request_type" value="first_time_job_seeker">
        <br>
        <h1>FIRST-TIME JOB SEEKER REQUEST</h1>
        <br>
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" placeholder="Juan Delacruz" required>
        <br>
        <label for="complete_address">Complete Address</label>
        <input type="text" name="complete_address" placeholder="House No., Street, Zone" required autocomplete="street-address">
        <br>
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>
        <br>
        <label for="contact_number">Contact Number</label>
        <input type="tel" id="contact_number" name="contact_number" placeholder="09xxxxxxxxx" required pattern="[0-9]{11}">
        <br>
        <label for="email_address">Email Address</label>
        <input type="email" id="email_address" name="email_address" placeholder="Enter your email address" required autocomplete="email">
        <br>
        <label for="remarks">Remarks/Additional Info</label>
        <textarea name="remarks" rows="5" cols="40" placeholder="Write here..."></textarea>
        <br>
        <label for="specify_others">Specify if "Others"</label>
        <textarea name="specify_others" rows="5" cols="40" placeholder="Write here..."></textarea>
        <br>
        <label for="valid_id_path">Upload Valid ID:</label>
        <input type="file" name="valid_id_path" accept=".jpg, .jpeg, .png" required>
        <br>
        <label for="proof_of_residency_path">Upload Proof of Residency:</label>
        <input type="file" name="proof_of_residency_path" accept=".jpg, .jpeg, .png" required>
        <br>
        <br>
        <a href="{{ route('barangay_system.services') }}">
            <button type="button">Back</button>
        </a>
        <button type="submit">Submit</button>
    </form>
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