<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyChat</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <h2><img src="{{ asset('images/Logo.png') }}" alt="Chat Logo" style="width: 150px; height: auto;"></h2>
            
            <script>
                let pollingInterval = null; // Menyimpan jadwal agar bisa dihentikan saat ganti kontak
                const myId = {{ auth()->id() }}; // Menyimpan ID user yang sedang login

                function selectContact(name, userId) {
                    const chatHeader = document.querySelector('.chat-header h3');
                    chatHeader.textContent = name;

                    // Set the recipient ID in the hidden input field
                    const recipientIdInput = document.getElementById('receiver_id');
                    recipientIdInput.value = userId;

                    // Enable the message input and send button
                    document.getElementById('msgInput').disabled = false;
                    document.getElementById('sendBtn').disabled = false;

                    // Simpan ke Local Storage (seperti yang kita buat sebelumnya)
                    localStorage.setItem('lastChatUserId', userId);
                    localStorage.setItem('lastChatUserName', name);

                    // --- SISTEM POLLING DIMULAI ---
                    
                    // 1. Hentikan polling dari kontak sebelumnya jika ada
                    if (pollingInterval) {
                        clearInterval(pollingInterval);
                    }

                    // 2. Langsung ambil pesan saat kontak di-klik (tidak perlu nunggu 3 detik pertama)
                    fetchMessages(userId);

                    // 3. Mulai jadwal cek pesan baru setiap 3 detik (3000 ms)
                    pollingInterval = setInterval(() => {
                        fetchMessages(userId);
                    }, 3000); 
                }

                // Fungsi untuk menarik data pesan dari Laravel (AJAX)
                function fetchMessages(userId) {
                    fetch(`/messages/${userId}`)
                        .then(response => response.json())
                        .then(messages => {
                            const messageDisplay = document.getElementById('messageDisplay');
                            
                            // Cek apakah user sedang scroll ke atas (agar layar tidak dipaksa ke bawah saat dia baca pesan lama)
                            const isScrolledToBottom = messageDisplay.scrollHeight - messageDisplay.clientHeight <= messageDisplay.scrollTop + 5;

                            // Kosongkan layar pesan
                            messageDisplay.innerHTML = '';

                            if (messages.length > 0) {
                                messages.forEach(message => {
                                    const messageDiv = document.createElement('div');
                                    messageDiv.classList.add('message');

                                    // Bedakan warna chat pengirim dan penerima
                                    if (message.sender_id === myId) {
                                        messageDiv.classList.add('sent');
                                    } else {
                                        messageDiv.classList.add('received');
                                    }

                                    const time = new Date(message.created_at).toLocaleTimeString([], { 
                                        hour: '2-digit', minute: '2-digit' 
                                    });

                                    messageDiv.innerHTML = `
                                        <p>${message.content}</p>
                                        <span class="time">${time}</span>
                                    `;
                                    messageDisplay.appendChild(messageDiv);
                                });
                            } else {
                                messageDisplay.innerHTML = '<div class="no-messages">Belum ada pesan.</div>';
                            }

                            // Otomatis scroll ke bawah JIKA sebelumnya posisi scroll ada di bawah
                            if (isScrolledToBottom) {
                                messageDisplay.scrollTop = messageDisplay.scrollHeight;
                            }
                        })
                        .catch(error => console.error('Error fetching messages:', error));
                }

                // Fungsi Mengirim Pesan Tanpa Reload
                function sendMessage(event) {
                    event.preventDefault(); // Mencegah form reload halaman

                    const receiverId = document.getElementById('receiver_id').value;
                    if (!receiverId) {
                        alert('Please select a contact before sending a message.');
                        return;
                    }

                    const form = document.getElementById('chatForm');
                    const formData = new FormData(form);
                    const msgInput = document.getElementById('msgInput');

                    msgInput.value = ''; // Kosongkan kotak ketik
                    msgInput.focus();    // Kembalikan kursor ke kotak ketik

                    // Kirim data ke backend
                    fetch("{{ route('send') }}", {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Setelah terkirim, paksa update layar agar pesan langsung muncul (tanpa nunggu 3 detik)
                        fetchMessages(receiverId); 
                    })
                    .catch(error => console.error('Error sending message:', error));
                }

                // Cek Local Storage saat halaman di-refresh
                document.addEventListener('DOMContentLoaded', function() {
                    const lastUserId = localStorage.getItem('lastChatUserId');
                    const lastUserName = localStorage.getItem('lastChatUserName');

                    if (lastUserId && lastUserName) {
                        selectContact(lastUserName, parseInt(lastUserId));
                    }
                });
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
                <h3>OnlyChat</h3>
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

            <form id="chatForm" class="input-area" onsubmit="sendMessage(event)">
                @csrf
                <input type="hidden" name="receiver_id" id="receiver_id">
                <input type="text" id="msgInput" name="message" placeholder="Type a message..." autocomplete="off" required disabled>
                <button type="submit" id="sendBtn" disabled>Send</button>
            </form>
        </main>
    </div>
</body>
</html>