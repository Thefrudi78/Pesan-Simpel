<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnlyChat</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Badge enkripsi di header */
        .encryption-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 12px;
            padding: 2px 10px;
            margin-left: 10px;
            vertical-align: middle;
        }
        .encryption-badge.hidden { display: none; }

        /* Indikator kunci di tiap bubble pesan */
        .message p::after {
            content: ' 🔒';
            font-size: 10px;
            opacity: 0.5;
        }

        /* Toast notifikasi error */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #f44336;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            display: none;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <aside class="sidebar">
            <h2>
                <img src="{{ asset('images/Logo.png') }}" alt="Chat Logo" style="width: 150px; height: auto;">
            </h2>

            @foreach ($users as $user)
                <div class="contact" onclick="selectContact('{{ $user->name }}', {{ $user->id }})">
                    {{ $user->name }}
                </div>
            @endforeach

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </aside>

        <main class="chat-area">
            <header class="chat-header">
                <h3>
                    OnlyChat
                    {{-- Badge muncul saat kontak dipilih --}}
                    <span class="encryption-badge hidden" id="encryptionBadge">
                        🔐 DH + AES-256 Encrypted
                    </span>
                </h3>
            </header>

            <div class="message-display" id="messageDisplay">
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
                <input type="text" id="msgInput" name="message"
                       placeholder="Type a message..." autocomplete="off" required disabled>
                <button type="submit" id="sendBtn" disabled>Send</button>
            </form>
        </main>
    </div>

    {{-- Toast error --}}
    <div class="toast" id="errorToast"></div>

    <script>
        let pollingInterval = null;
        const myId = {{ auth()->id() }};

        // ── Tampilkan toast error ──────────────────────────────────────────────
        function showError(msg) {
            const toast = document.getElementById('errorToast');
            toast.textContent = msg;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 4000);
        }

        // ── Pilih kontak ──────────────────────────────────────────────────────
        function selectContact(name, userId) {
            // Update header
            document.querySelector('.chat-header h3').childNodes[0].textContent = name + ' ';

            // Tampilkan badge enkripsi
            document.getElementById('encryptionBadge').classList.remove('hidden');

            // Set receiver
            document.getElementById('receiver_id').value = userId;

            // Enable input
            document.getElementById('msgInput').disabled = false;
            document.getElementById('sendBtn').disabled  = false;

            // Simpan ke localStorage
            localStorage.setItem('lastChatUserId',   userId);
            localStorage.setItem('lastChatUserName', name);

            // Hentikan polling lama, mulai polling baru
            if (pollingInterval) clearInterval(pollingInterval);

            fetchMessages(userId); // langsung ambil pesan
            pollingInterval = setInterval(() => fetchMessages(userId), 3000);
        }

        // ── Ambil & render pesan (sudah terdekripsi oleh server) ─────────────
        function fetchMessages(userId) {
            fetch(`/messages/${userId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil pesan');
                    return res.json();
                })
                .then(messages => {
                    const display = document.getElementById('messageDisplay');
                    const isAtBottom = display.scrollHeight - display.clientHeight
                                       <= display.scrollTop + 5;

                    display.innerHTML = '';

                    if (messages.length === 0) {
                        display.innerHTML = '<div class="no-messages">Belum ada pesan.</div>';
                        return;
                    }

                    messages.forEach(msg => {
                        const div  = document.createElement('div');
                        const time = new Date(msg.created_at)
                            .toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        div.classList.add('message', msg.sender_id === myId ? 'sent' : 'received');
                        div.innerHTML = `<p>${msg.content}</p><span class="time">${time}</span>`;
                        display.appendChild(div);
                    });

                    if (isAtBottom) display.scrollTop = display.scrollHeight;
                })
                .catch(err => {
                    console.error('fetchMessages error:', err);
                    showError('⚠️ Gagal memuat pesan. Pastikan key enkripsi tersedia.');
                });
        }

        // ── Kirim pesan (enkripsi dilakukan server-side) ──────────────────────
        function sendMessage(event) {
            event.preventDefault();

            const receiverId = document.getElementById('receiver_id').value;
            if (!receiverId) {
                alert('Pilih kontak terlebih dahulu.');
                return;
            }

            const msgInput = document.getElementById('msgInput');
            const formData = new FormData(document.getElementById('chatForm'));

            // Kosongkan input sebelum fetch agar terasa responsif
            msgInput.value = '';
            msgInput.focus();

            fetch("{{ route('send') }}", {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal mengirim pesan');
                return res.json();
            })
            .then(() => {
                // Paksa refresh pesan agar langsung muncul tanpa nunggu polling
                fetchMessages(receiverId);
            })
            .catch(err => {
                console.error('sendMessage error:', err);
                showError('⚠️ Gagal mengirim pesan. Coba lagi.');
            });
        }

        // ── Restore kontak terakhir saat refresh ──────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const lastId   = localStorage.getItem('lastChatUserId');
            const lastName = localStorage.getItem('lastChatUserName');
            if (lastId && lastName) selectContact(lastName, parseInt(lastId));
        });
    </script>
</body>
</html>