# Laravel Broadcasting with Reverb

This repository demonstrates how to use Laravel Broadcasting with Reverb to handle real-time events using different types of channels: private, public, presence, and whispering channels.

## Installation & Setup

### Prerequisites
Ensure you have the following installed:
- PHP
- Laravel framework
- Node.js & npm
- Reverb server (for real-time event handling)
- Laravel Echo & Pusher (or Reverb-compatible client)

### Installation Steps
1. Clone the repository:
   ```sh
   git clone https://github.com/your-repo-name.git
   cd your-repo-name
   ```
2. Install dependencies:
   ```sh
   composer install
   npm install
   ```
3. Copy the `.env.example` file to `.env` and configure the database and Reverb credentials.
4. Generate application key:
   ```sh
   php artisan key:generate
   ```
5. Run migrations:
   ```sh
   php artisan migrate
   ```
6. Start the Laravel application:
   ```sh
   php artisan serve
   ```
7. Start the Reverb server (if not already running):
   ```sh
   reverb start
   ```
8. Compile frontend assets:
   ```sh
   npm run dev
   ```

## Broadcasting Channels
Laravel uses broadcasting to send real-time events. Here are the types of channels implemented:

### Private Channel
```php
Broadcast::channel('private-channel-{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
```
- Used for one-on-one secure communication.
- Authentication required.

### Public Channel
```php
Broadcast::channel('chat', function ($user) {
    return true;
});
```
- Open to all users without authentication.
- Suitable for global messages.

### Presence Channel
```php
Broadcast::channel('presence-channel', function (User $user) {
    return [
        'id' => $user->id,
        'email' => $user->email,
    ];
});
```
- Tracks online users in a channel.
- Returns user data when they join.

### Whisper Channel
```php
Broadcast::channel('whisper-channel', function (User $user) {
    return true;
});
```
- Enables real-time interactions without passing through Laravel.
- Clients communicate directly via Reverb.

## Frontend Implementation
The frontend utilizes Laravel Echo to listen for events and handle real-time interactions.

### Example Usage
```js
Echo.channel('chat')
    .listen('SendMessageEvent', (event) => {
        console.log(event);
    });

var id = @json(auth()->id());
var channelName = `private-channel-${id}`;
Echo.private(channelName)
    .listen('PrivateChannelEvent', (event) => {
        console.log(event);
    });

Echo.join('presence-channel')
    .here(users => {
        console.log('Users in room:', users);
    })
    .joining(user => {
        console.log(`${user.email} joined the room`);
    })
    .leaving(user => {
        console.log(`${user.email} left the room`);
    })
    .listen('SendMessageEvent', (event) => {
        console.log('New message:', event.message);
    });

const whisperEcho = Echo.private('whisper-channel');
setInterval(() => {
    whisperEcho.whisper('typing', { message: 'Hello, I am typing...' });
}, 2000);
whisperEcho.listenForWhisper('typing', (event) => {
    console.log(event);
});
```

## Conclusion
This project demonstrates how to set up Laravel Broadcasting with Reverb, implement different channels, and use Laravel Echo for real-time communication.

For any questions, feel free to open an issue!

---

**Author:** Your Name  
**License:** MIT

