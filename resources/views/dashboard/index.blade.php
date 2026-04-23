<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SimpleChat UI</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <h2>Chats</h2>
            @foreach ($users as $user)
                <div class="contact">{{ $user->name }}</div>
            @endforeach
            <form method="POST" action="{{ route('logout') }}" style="position: absolute; bottom: 10px; left: 10px;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </aside>

        <main class="chat-area">
            <header class="chat-header">
                <h3>General Room</h3>
            </header>

            <div class="message-display" id="messageDisplay">
                <div class="message received">
                    <p>Test</p>
                    <span class="time">10:15</span>
                </div>
            </div>

            <form class="input-area" id="chatForm">
                <input type="text" id="msgInput" placeholder="Type a message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </main>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>