<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('private-channel-{id}', function ($user, $id) {
    // the first argument is alsways the logged in user and the second one is the id of the channel we are seding through frontend
    return (int) $user->id === (int) $id;
});

/* Reverb Working
so what happens is k reverb ka server elhda port and ip p listen kr rha hota jo hm specify krty env  main
ab hota yeh h k pehly client ek  channel ko subscribe krta hai jo laravel echo main s krta ..reveb app id and key s and reverb app key s ..
phir reverb server jo h woh laravel ko bhejta k yeh authnticated hai ya nahi woh phr channel.php main ati and if reurn ture tu authenitcated
phir woh reverb secret s ek data type bna k sign kr k bhejta reverb server ko .. tu phir woh subscribe ho jata hai client and aisy e reverb server agy yeh
event wagaira agy client ko phchata hai ..

*/

Broadcast::channel('chat', function ($user) {
    return true;  // as it is a public channel so any one can join that and for public channel thereis no authorizations
});


/*
presence channel  works in a way that from the channel we return the data our channel needs to hold
because presence channel is all about who is listening in the channel
and if we trigge the event it will send the data same like priate or public channel
*/
Broadcast::channel('presence-channel', function (User $user) {

    return [
        'id' => $user->id,
        'email' => $user->email,
    ];
});


/*

Standard Event: Client -> Reverb -> Laravel -> Reverb -> Other Clients

Whispering: Client -> Reverb -> Other Clients (Laravel is bypassed)

whispering is that we join  a channel like a private channel and after that client emit a event to reverb to send it to all connected client
direclty and dont involve laravel in it

*/

Broadcast::channel('whisper-channel', function (User $user) {
    return true;
});
