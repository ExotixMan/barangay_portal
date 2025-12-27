@extends('layout.default')
<title>Services - Barangay Hulo Portal</title>
@section('contents')
    <form method="get" action="{{ route('barangay_system.services')}}">
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
        <br>
        <table border=1>  
            @if (session()->has('success'))
                <div>
                    {{session('success')}}
                </div>
            @endif
            <tr>
                <td><a href="{{ route('indigency.req') }}">Request for Indigency Certificate</a></td>
            </tr>
            <tr>
                <td><a href="{{ route('job_seeker.req') }} ">Request for First-Time Job Seeker Certificate</a></td>
            </tr>
            <tr>
                <td><a href="{{ route('clearance.req') }} ">Request for Barangay Clearance</a></td>
            </tr>
        </table>
    </form>
    <h1>Request Status</h1>
    @if($requests->isEmpty())
    <p>No requests found.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>Request Type</th>
                    <th>Full Name</th>
                    <th>Complete Address</th>
                    <th>Age</th>
                    <th>Date of Birth</th>
                    <th>Contact Number</th>
                    <th>Email Address</th>
                    <th>Purpose of Request</th>
                    <th>Specify Others</th>
                    <th>Valid ID</th>
                    <th>Proof of Residency</th>
                    <th>Remarks</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr>
                    <td>{{ $request->request_type }}</td>
                    <td>{{ $request->full_name }}</td>
                    <td>{{ $request->complete_address }}</td>
                    <td>{{ $request->age ?? '-' }}</td>
                    <td>{{ $request->date_of_birth ?? '-' }}</td>
                    <td>{{ $request->contact_number }}</td>
                    <td>{{ $request->email_address ?? '-' }}</td>
                    <td>{{ $request->purpose_of_request ?? '-' }}</td>
                    <td>{{ $request->specify_others ?? '-' }}</td>
                    <td>
                        @if($request->valid_id_path)
                            <a href="{{ asset($request->valid_id_path) }}" target="_blank">View</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($request->proof_of_residency_path)
                            <a href="{{ asset($request->proof_of_residency_path) }}" target="_blank">View</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $request->remarks ?? '-' }}</td>
                    <td>{{ $request->date_submitted }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
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
@endsection