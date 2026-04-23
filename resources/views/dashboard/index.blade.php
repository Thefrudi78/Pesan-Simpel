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
            <h2><img src="{{ asset('images/Logo.png') }}" alt="Chat Logo" style="width: 150px; height: auto;"></h2>
            <script>
                // Ensure this is an object, even if empty
                const messages = @json($messages) || {};

                function selectContact(name, userId) {
                    const chatHeader = document.querySelector('.chat-header h3');
                    chatHeader.textContent = name;

                    const messageDisplay = document.getElementById('messageDisplay');
                    messageDisplay.innerHTML = '';

                    // Set the recipient ID in the hidden input field
                    const recipientIdInput = document.getElementById('receiver_id');
                    recipientIdInput.value = userId;

                    // Check if there are messages for this specific sender (userId)
                    if (messages[userId] && messages[userId].length > 0) {
                        messages[userId].forEach(message => {
                            const messageDiv = document.createElement('div');
                            messageDiv.classList.add('message', 'received');
                            
                            // Format the date/time (optional improvement)
                            const time = new Date(message.created_at).toLocaleTimeString([], { 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });

                            messageDiv.innerHTML = `
                                <p>${message.content}</p>
                                <span class="time">${time}</span>
                            `;
                            messageDisplay.appendChild(messageDiv);
                        });
                    } else {
                        messageDisplay.innerHTML = '<div class="no-messages">No messages from this user.</div>';
                    }
                }
            </script>

            @foreach ($users as $user)
                <div class="contact" onclick="selectContact('{{ $user->name }}', {{ $user->id }})">{{ $user->name }}</div>
            @endforeach

            <form method="POST" action="{{ route('logout') }}" style="position: absolute; bottom: 10px; left: 10px;">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </aside>

        <main class="chat-area">
            <header class="chat-header">
                <h3>System</h3>
            </header>

            <div class="message-display" id="messageDisplay">
                <div class="message received">
                    <p>Select one of the contacts to start chatting.</p>
                    <span class="time">{{ now()->format('H:i') }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('send') }}" class="input-area">
                @csrf
                <input type="hidden" name="receiver_id" id="receiver_id">
                <input type="text" id="msgInput" name="message" placeholder="Type a message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </main>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>