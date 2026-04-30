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

                    // Enable the message input and send button now that a contact is selected
                    document.getElementById('msgInput').disabled = false;
                    document.getElementById('sendBtn').disabled = false;

                    // --- TAMBAHAN: Simpan kontak terakhir ke Local Storage ---
                    localStorage.setItem('lastChatUserId', userId);
                    localStorage.setItem('lastChatUserName', name);
                    // --------------------------------------------------------

                    // Check if there are messages for this specific sender (userId)
                    if (messages[userId] && messages[userId].length > 0) {
                        messages[userId].forEach(message => {
                            const messageDiv = document.createElement('div');
                            messageDiv.classList.add('message');

                            // Determine if the message is sent or received
                            if (message.sender_id === {{ auth()->id() }}) {
                                messageDiv.classList.add('sent');
                            } else {
                                messageDiv.classList.add('received');
                            }

                            // Format the date/time
                            const time = new Date(message.created_at).toLocaleTimeString([], { 
                                hour: '2-digit', 
                                minute: '2-digit' 
                            });

                            messageDiv.innerHTML = `
                                <p>${message.content}</p>
                                <span class="time">${time}</span>
                            `;
                            messageDisplay.appendChild(messageDiv); // Append to the bottom
                        });
                    } else {
                        messageDisplay.innerHTML = '<div class="no-messages">No messages from this user.</div>';
                    }
                    
                    // Opsional: Scroll otomatis ke bawah agar pesan terbaru langsung terlihat
                    messageDisplay.scrollTop = messageDisplay.scrollHeight;
                }

                // Client-side validation before submitting
                function validateForm(event) {
                    const receiverId = document.getElementById('receiver_id').value;
                    if (!receiverId) {
                        event.preventDefault();
                        alert('Please select a contact before sending a message.');
                        return false;
                    }
                    return true;
                }

                // --- TAMBAHAN: Cek Local Storage saat halaman selesai di-reload ---
                document.addEventListener('DOMContentLoaded', function() {
                    const lastUserId = localStorage.getItem('lastChatUserId');
                    const lastUserName = localStorage.getItem('lastChatUserName');

                    // Jika ada data kontak yang tersimpan, panggil fungsi selectContact secara otomatis
                    if (lastUserId && lastUserName) {
                        selectContact(lastUserName, parseInt(lastUserId));
                        
                        // FOKUSKAN kursor kembali ke kolom input setelah halaman dimuat
                        document.getElementById('msgInput').focus();
                    }
                });
                // ------------------------------------------------------------------
            </script>

            @foreach ($users as $user)
                <div class="contact" onclick="selectContact('{{ $user->name }}', {{ $user->id }})">{{ $user->name }}</div>
            @endforeach

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </aside>

        <main class="chat-area">
            <header class="chat-header">
                <h3>System</h3>
            </header>

            <div class="message-display" id="messageDisplay">
                {{-- Display Backend Errors if any --}}
                @if ($errors->any())
                    <div class="error-messages" style="color: red; padding: 10px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="message received">
                    <p>Select one of the contacts to start chatting.</p>
                    <span class="time">{{ now()->format('H:i') }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('send') }}" class="input-area" id="chatForm" onsubmit="return validateForm(event)">
                @csrf
                <input type="hidden" name="receiver_id" id="receiver_id">
                {{-- Added 'required' and disabled by default until a contact is clicked --}}
                <input type="text" id="msgInput" name="message" placeholder="Type a message..." autocomplete="off" required disabled>
                <button type="submit" id="sendBtn" disabled>Send</button>
            </form>
        </main>
    </div>
</body>
</html>