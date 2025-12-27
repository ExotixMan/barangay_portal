<!DOCTYPE html>
<html lang=en>
<head>
    <title>Barangay Hulo</title>  
</head>
<body>
    <table border=1>
        <tr>
            <th><a href="{{ route('barangay_system.index')}}">{{ __('messages.home') }}</a></th>
            <th><a href="">{{ __('messages.about') }}</a></th>
            <th><a href="{{ route('barangay_system.services') }}">{{ __('messages.services') }}</a></th>
            <th><a href= >{{ __('messages.community') }}</a></th>
            <th><a href="{{ route('barangay_system.incident') }}">{{ __('messages.report') }}</a></th>
            <th><a href= >{{ __('messages.contact') }}</a></th>
            <th><a href="{{ route('login.res') }}">{{ __('messages.login') }}</a></th>
            <th><a href="{{ route('logout.res') }}">{{ __('messages.logout') }}</a></th>
        </tr>
    </table>
    <br>
    @if (session()->has('success'))
        <div>
            {{session('success')}}
        </div>
    @endif
    <br>
    <table border=1>
        <tr>
            <td><a href= >Learn more about Barangay Hulo</a></td>
        </tr>
        <tr>
            <td><a href="{{ route('barangay_system.services') }}">Request Barangay documents & Services here</a></td>
        </tr>
        <tr>
            <td><a href= >Stay Updated with Our Community</a></td>
        </tr>
        <tr>
            <td><a href="{{ route('barangay_system.incident') }}">Report a Concern or Incedent here</a></td>
        </tr>
        <tr>
            <td><a href= >Get in Touch with Barangay Hulo</a></td>
        </tr>
    </table>
    <br>
    <a href="{{ route('switch.language', ['lang' => 'en']) }}">English</a> |
    <a href="{{ route('switch.language', ['lang' => 'tl']) }}">Filipino</a>

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