<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        Echo.channel(
                'chat'
            ) // here first come the channel name defined in the channel.php  and in listen comes the event name
            .listen('SendMessageEvent', (event) => {
                console.log(event);
            });
        var id = @json(auth()->id());
        var channelName = `private-channel-${id}`;
        Echo.private(channelName) //
            .listen('PrivateChannelEvent', (event) => {
                console.log(event);
            });
        var channel = `presence-channel`;
        Echo.join(channel) //
            .here(users => {
                console.log("Users in room:",
                    users
                ); // currently all data in the channel .. only runs once at the start. at that whatever the data channel holds come here
            })
            .joining(user => {
                console.log(
                    `${user.email} joined the room`
                ); // whenever mewanwhil a new user joins the channel .. the above function dont run after this. we need to manually update our current users list
            })
            .leaving(user => {
                console.log(`${user.email} left the room`); // when a user leaves the channel
            })
            .listen("SendMessageEvent", (event) => {
                console.log("New message:", event
                    .message); // the data transferred from the event other than user list form channel.php
            });


        var whisperChannel = 'whisper-channel'

      const whisperEcho = Echo.private(
            whisperChannel
        ) // subscribe to the channel for first time authenticatoin .. after that we will ask reverb

        setInterval(() => {
            whisperEcho.whisper(
                'typing', { // after 2000ms we send an event of typing to reverb with message data
                    message: 'Hello, I am typing...'
                });
        }, 2000);

        whisperEcho.listenForWhisper('typing', (event) => {
            console.log(event); // what we got directly from the directly event form the client
        });
    });
</script>

</html>
