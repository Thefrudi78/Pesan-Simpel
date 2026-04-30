<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>OnlyChat - Secure Messaging</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* =========================================================
           OnlyChat — Premium Encrypted Messaging UI
           Theme: Dark sidebar + Light chat area (Telegram-inspired)
           Accent: Teal/Emerald (encryption vibe)
           ========================================================= */

        /* ---------- 1. CSS Variables & Design Tokens ---------- */
        :root {
            --brand-primary: #0ea5a4;
            --brand-primary-dark: #0d9488;
            --brand-gradient: linear-gradient(135deg, #14b8a6 0%, #0ea5a4 50%, #0d9488 100%);
            --brand-glow: rgba(20, 184, 166, 0.35);

            --sidebar-bg: #0f1720;
            --sidebar-bg-elevated: #151f2a;
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --sidebar-text: #e5e7eb;
            --sidebar-text-muted: #94a3b8;
            --sidebar-hover: rgba(255, 255, 255, 0.04);
            --sidebar-active: rgba(20, 184, 166, 0.12);
            --sidebar-active-border: #14b8a6;

            --chat-bg: #f1f5f9;
            --chat-bg-pattern: #e2e8f0;
            --chat-surface: #ffffff;
            --chat-border: #e2e8f0;
            --chat-text: #0f172a;
            --chat-text-muted: #64748b;
            --chat-header-bg: rgba(255, 255, 255, 0.85);

            --bubble-sent-bg: linear-gradient(135deg, #14b8a6 0%, #0ea5a4 100%);
            --bubble-sent-text: #ffffff;
            --bubble-received-bg: #ffffff;
            --bubble-received-text: #0f172a;
            --bubble-received-border: #e2e8f0;

            --success: #10b981;
            --success-bg: rgba(16, 185, 129, 0.1);
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --warning: #f59e0b;

            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 12px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 12px 32px rgba(15, 23, 42, 0.12);
            --shadow-xl: 0 20px 50px rgba(15, 23, 42, 0.18);
            --shadow-glow: 0 8px 24px rgba(20, 184, 166, 0.3);

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 999px;

            --ease: cubic-bezier(0.4, 0, 0.2, 1);
            --ease-bounce: cubic-bezier(0.34, 1.56, 0.64, 1);
            --transition-fast: 150ms var(--ease);
            --transition-base: 250ms var(--ease);
            --transition-slow: 400ms var(--ease);

            --sidebar-width: 320px;
            --header-height: 72px;
            --input-height: 76px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            font-size: 15px;
            line-height: 1.5;
            color: var(--chat-text);
            background: var(--chat-bg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            display: flex;
        }

        button {
            font-family: inherit;
            border: none;
            cursor: pointer;
            background: none;
            color: inherit;
        }

        input {
            font-family: inherit;
            border: none;
            outline: none;
            background: none;
            color: inherit;
        }

        /* ---------- SIDEBAR ---------- */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            height: 100%;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
            position: relative;
            z-index: 40;
            transition: transform var(--transition-base);
        }

        .sidebar-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: var(--header-height);
            flex-shrink: 0;
        }

        .sidebar-header img {
            height: 70px;
            object-fit: cover;
            padding: 4px;
            transition: transform var(--transition-base);
        }

        .sidebar-header img:hover {
            transform: scale(1.05) rotate(-3deg);
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            background: var(--brand-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-profile {
            padding: 14px 16px;
            margin: 0 8px 10px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--sidebar-border);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #14b8a6, #0d9488);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 0 0 1px rgba(20, 184, 166, 0.2);
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            letter-spacing: -0.01em;
        }

        .user-status {
            font-size: 11px;
            font-weight: 500;
            color: #10b981;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        .contacts-list {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 12px 8px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .contacts-list::before {
            content: "Contacts";
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--sidebar-text-muted);
            padding: 8px 14px 10px;
        }

        .contact {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin: 0 4px;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-fast);
            color: var(--sidebar-text);
            font-weight: 500;
            font-size: 14.5px;
            position: relative;
            border: 1px solid transparent;
            user-select: none;
        }

        .contact::before {
            content: "";
            width: 38px;
            height: 38px;
            min-width: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #334155, #1e293b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #cbd5e1;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
        }

        .contact:hover {
            background: var(--sidebar-hover);
            transform: translateX(2px);
        }

        .contact.active {
            background: var(--sidebar-active);
            border-color: rgba(20, 184, 166, 0.25);
            color: #fff;
        }

        .contact.active::after {
            content: "";
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--brand-gradient);
            border-radius: var(--radius-full);
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .logout-btn {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            background: rgba(239, 68, 68, 0.08);
            color: #fca5a5;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all var(--transition-fast);
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.15);
            color: #fecaca;
            border-color: rgba(239, 68, 68, 0.3);
            transform: translateY(-1px);
        }

        .logout-btn::before {
            content: "⏻";
            font-size: 16px;
        }

        /* ---------- MAIN CHAT AREA ---------- */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: var(--chat-bg);
            position: relative;
            min-width: 0;
            background-image:
                radial-gradient(circle at 1px 1px, var(--chat-bg-pattern) 1px, transparent 0);
            background-size: 24px 24px;
        }

        .chat-header {
            min-height: var(--header-height);
            padding: 14px 24px;
            background: var(--chat-header-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--chat-border);
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
            z-index: 10;
        }

        #mobileToggle {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: var(--chat-surface);
            border: 1px solid var(--chat-border);
            color: var(--chat-text);
            align-items: center;
            justify-content: center;
            transition: all var(--transition-fast);
            flex-shrink: 0;
        }

        #mobileToggle:hover {
            background: #f8fafc;
            transform: scale(1.05);
        }

        #headerTitle {
            font-size: 16px;
            font-weight: 700;
            color: var(--chat-text);
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .encryption-badge {
            display: none;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            background: var(--success-bg);
            color: var(--success);
            border-radius: var(--radius-full);
            border: 1px solid rgba(16, 185, 129, 0.2);
            letter-spacing: 0.01em;
            white-space: nowrap;
            animation: badge-glow 3s ease-in-out infinite;
        }

        @keyframes badge-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
            50% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.08); }
        }

        /* ---------- WELCOME SCREEN ---------- */
        .welcome-screen {
            flex: 1;
            display: none;
            overflow-y: auto;
            padding: 40px 20px;
        }

        .welcome-screen.active {
            display: flex;
        }

        .welcome-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: auto;
            max-width: 420px;
            animation: fade-in 0.6s var(--ease);
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-icon {
            font-size: 72px;
            margin-bottom: 24px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        .welcome-content h2 {
            font-size: 28px;
            font-weight: 800;
            color: var(--chat-text);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .welcome-content > p {
            font-size: 16px;
            color: var(--chat-text-muted);
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .welcome-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 32px;
            width: 100%;
        }

        .feature {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
            background: var(--chat-surface);
            border: 1px solid var(--chat-border);
            border-radius: var(--radius-md);
            transition: all var(--transition-base);
        }

        .feature:hover {
            background: #f8fafc;
            border-color: var(--brand-primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .feature-icon { font-size: 28px; min-width: 40px; display: flex; align-items: center; justify-content: center; }
        .feature-text { display: flex; flex-direction: column; align-items: flex-start; text-align: left; }
        .feature-text strong { font-size: 14px; font-weight: 700; color: var(--chat-text); display: block; margin-bottom: 2px; }
        .feature-text span { font-size: 12px; color: var(--chat-text-muted); font-weight: 500; }

        .welcome-hint {
            font-size: 14px;
            color: var(--chat-text-muted);
            font-weight: 500;
        }

        /* ---------- MESSAGE DISPLAY ---------- */
        .message-display {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 24px 20px 20px;
            display: none;
            flex-direction: column;
            gap: 6px;
            scroll-behavior: smooth;
        }

        .message-display.active {
            display: flex;
        }

        .message-display:empty::before {
            content: "🔐\A End-to-end encrypted\A Select a contact to start secure messaging";
            white-space: pre;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--chat-text-muted);
            font-size: 14px;
            line-height: 2;
            height: 100%;
            font-weight: 500;
        }

        .message {
            max-width: 72%;
            padding: 10px 14px 8px;
            border-radius: var(--radius-lg);
            position: relative;
            word-wrap: break-word;
            word-break: break-word;
            font-size: 14.5px;
            line-height: 1.45;
            animation: bubble-in 0.3s var(--ease-bounce);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2px;
        }

        @keyframes bubble-in {
            from { opacity: 0; transform: translateY(8px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .message.sent {
            background: var(--bubble-sent-bg);
            color: var(--bubble-sent-text);
            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 6px;
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.25);
        }

        .message.received {
            background: var(--bubble-received-bg);
            color: var(--bubble-received-text);
            align-self: flex-start;
            margin-right: auto;
            border: 1px solid var(--bubble-received-border);
            border-bottom-left-radius: 6px;
        }

        .message small, .message .time {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 500;
            opacity: 0.75;
            margin-left: 8px;
            margin-top: 2px;
            font-family: 'JetBrains Mono', ui-monospace, monospace;
            letter-spacing: -0.02em;
            vertical-align: baseline;
        }

        .message.sent .time { color: rgba(255, 255, 255, 0.85); }
        .message.received .time { color: var(--chat-text-muted); }

        /* Lock icon */
        .message p::after {
            content: ' ';
            font-size: 10px;
            opacity: 0.5;
        }

        /* ---------- INPUT AREA ---------- */
        .input-area {
            padding: 14px 20px 18px;
            background: var(--chat-header-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-top: 1px solid var(--chat-border);
            display: none;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .input-area.active {
            display: flex;
        }

        #msgInput {
            flex: 1;
            padding: 13px 18px;
            background: var(--chat-surface);
            border: 1.5px solid var(--chat-border);
            border-radius: var(--radius-full);
            font-size: 14.5px;
            color: var(--chat-text);
            transition: all var(--transition-fast);
            min-width: 0;
        }

        #msgInput::placeholder { color: var(--chat-text-muted); font-weight: 400; }
        #msgInput:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
            background: #fff;
        }

        #sendBtn {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 50%;
            background: var(--brand-gradient);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-base);
            box-shadow: var(--shadow-glow);
            font-size: 0;
            position: relative;
            flex-shrink: 0;
        }

        #sendBtn::before {
            content: "";
            width: 18px;
            height: 18px;
            background-color: #fff;
            -webkit-mask: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M22 2L11 13'/><path d='M22 2l-7 20-4-9-9-4 20-7z'/></svg>") center/contain no-repeat;
            mask: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'><path d='M22 2L11 13'/><path d='M22 2l-7 20-4-9-9-4 20-7z'/></svg>") center/contain no-repeat;
            transition: transform var(--transition-fast);
        }

        #sendBtn:hover:not(:disabled) {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 12px 28px rgba(20, 184, 166, 0.4);
        }

        #sendBtn:active:not(:disabled) { transform: translateY(0) scale(0.96); }
        #sendBtn:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; background: #cbd5e1; }

        /* ---------- TOAST ---------- */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            max-width: 360px;
            padding: 14px 18px 14px 44px;
            background: #fff;
            color: var(--chat-text);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            border-left: 4px solid var(--danger);
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            animation: toast-slide-in 0.4s var(--ease-bounce);
        }

        .toast::before {
            content: "⚠";
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            background: var(--danger-bg);
            color: var(--danger);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        @keyframes toast-slide-in {
            from { opacity: 0; transform: translateX(calc(100% + 24px)); }
            to { opacity: 1; transform: translateX(0); }
        }

        .toast.hiding {
            animation: toast-slide-out 0.3s var(--ease) forwards;
        }

        @keyframes toast-slide-out {
            to { opacity: 0; transform: translateX(calc(100% + 24px)); }
        }

        /* ---------- SCROLLBARS ---------- */
        .message-display::-webkit-scrollbar { width: 6px; }
        .message-display::-webkit-scrollbar-track { background: transparent; }
        .message-display::-webkit-scrollbar-thumb { background: rgba(100, 116, 139, 0.25); border-radius: var(--radius-full); }
        .contacts-list::-webkit-scrollbar { width: 6px; }
        .contacts-list::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.08); border-radius: var(--radius-full); }

        /* ---------- MOBILE BACKDROP ---------- */
        #sidebarBackdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 35;
            opacity: 0;
            transition: opacity var(--transition-base);
        }

        #sidebarBackdrop.active { display: block; opacity: 1; }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width: 768px) {
            :root { --sidebar-width: 285px; --header-height: 64px; }
            .sidebar {
                position: fixed; top: 0; left: 0; bottom: 0; height: 100%;
                width: var(--sidebar-width); transform: translateX(-100%);
                box-shadow: var(--shadow-xl); z-index: 50;
            }
            .sidebar.open { transform: translateX(0); }
            #mobileToggle { display: flex; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="h-full">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/Logo.png') }}" alt="OnlyChat Logo"
                 onerror="this.style.background='linear-gradient(135deg, #14b8a6, #0d9488)'; this.src='data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><text x=%2220%22 y=%2226%22 font-size=%2220%22 text-anchor=%22middle%22 fill=%22white%22>🔐</text></svg>';">
            
        </div>

        <!-- User Profile -->
        <div class="user-profile">
            <div class="user-avatar">👤</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-status">Online</div>
            </div>
        </div>

        <!-- Contacts -->
        <div id="users" class="contacts-list">
            @foreach ($users as $user)
                <div class="contact" onclick="selectContact('{{ $user->name }}', {{ $user->id }})">
                    {{ $user->name }}
                </div>
            @endforeach
        </div>

        <!-- Logout -->
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn" id="logoutBtn">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Mobile Backdrop -->
    <div id="sidebarBackdrop"></div>

    <!-- Main Chat -->
    <main class="chat-area">
        <header class="chat-header" id="chatHeader">
            <button id="mobileToggle" aria-label="Toggle menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <h2 id="headerTitle">
                Welcome to OnlyChat
                <span class="encryption-badge" id="encryptionBadge">🔐 DH + AES-256 Encrypted</span>
            </h2>
        </header>

        <!-- Welcome Screen -->
        <div id="welcomeScreen" class="welcome-screen active">
            <div class="welcome-content">
                <div class="welcome-icon">🔐</div>
                <h2>Welcome to OnlyChat</h2>
                <p>End-to-end encrypted messaging, just for you.</p>
                <div class="welcome-features">
                    <div class="feature">
                        <div class="feature-icon">🔒</div>
                        <div class="feature-text">
                            <strong>Military-grade encryption</strong>
                            <span>DH + AES-256 protection</span>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">👥</div>
                        <div class="feature-text">
                            <strong>Secure conversations</strong>
                            <span>Click a contact to start chatting</span>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">⚡</div>
                        <div class="feature-text">
                            <strong>Instant messaging</strong>
                            <span>Real-time encrypted delivery</span>
                        </div>
                    </div>
                </div>
                <p class="welcome-hint">👈 Select a contact from the list to begin</p>
            </div>
        </div>

        <!-- Message Display -->
        <div id="messageDisplay" class="message-display"></div>

        <!-- Input Form -->
        <form id="messageForm" class="input-area" onsubmit="sendMessage(event)">
            @csrf
            <input type="hidden" name="receiver_id" id="receiver_id">
            <input type="text" id="msgInput" name="message"
                   placeholder="Type a secure message..." autocomplete="off" required disabled>
            <button type="submit" id="sendBtn" disabled aria-label="Send message"></button>
        </form>
    </main>

    <!-- Toast Error -->
    <div class="toast" id="errorToast" style="display: none;"></div>

    <script>
        // ── SweetAlert Logout (backdrop dimatikan agar sidebar tidak terpotong) ──
        document.getElementById('logoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Logout?',
                text: 'Anda akan kembali ke halaman login.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal',
                backdrop: false,
                scrollbarPadding: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // ── Variabel global ─────────────────────────────────────────────────
        const myId = {{ auth()->id() }};
        let pollingInterval = null;
        const displayedMessageIds = new Set(); // Melacak ID pesan yang sudah ditampilkan

        // ── Fungsi untuk menambahkan satu pesan ke DOM (tanpa mengganti semuanya) ──
        function appendMessage(msg, container) {
            const div = document.createElement('div');
            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            div.classList.add('message', msg.sender_id === myId ? 'sent' : 'received');
            div.innerHTML = `<p>${msg.content}</p><span class="time">${time}</span>`;
            container.appendChild(div);
        }

        // ── Mobile Drawer ──────────────────────────────────────────────────
        (function() {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (!sidebar || !toggle || !backdrop) return;

            function openSidebar() {
                sidebar.classList.add('open');
                backdrop.classList.add('active');
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                backdrop.classList.remove('active');
            }

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
            });

            backdrop.addEventListener('click', closeSidebar);

            document.getElementById('users').addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && e.target.closest('.contact')) {
                    setTimeout(closeSidebar, 150);
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) closeSidebar();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeSidebar();
            });
        })();

        // ── Toast Error ────────────────────────────────────────────────────
        function showError(msg) {
            const toast = document.getElementById('errorToast');
            toast.textContent = msg;
            toast.style.display = 'block';
            toast.classList.remove('hiding');
            setTimeout(() => {
                toast.classList.add('hiding');
                setTimeout(() => { toast.style.display = 'none'; }, 300);
            }, 4000);
        }

        // ── Pilih Kontak ───────────────────────────────────────────────────
        function selectContact(name, userId) {
            document.querySelectorAll('#users .contact').forEach(el => el.classList.remove('active'));
            event.currentTarget.classList.add('active');

            const headerTitle = document.getElementById('headerTitle');
            if (headerTitle) {
                headerTitle.innerHTML = `${name} <span class="encryption-badge" id="encryptionBadge">🔐 DH + AES-256 Encrypted</span>`;
            }

            document.getElementById('welcomeScreen').classList.remove('active');
            document.getElementById('messageDisplay').classList.add('active');
            document.getElementById('messageForm').classList.add('active');

            document.getElementById('receiver_id').value = userId;
            document.getElementById('msgInput').disabled = false;
            document.getElementById('sendBtn').disabled = false;

            // Reset pelacak pesan
            displayedMessageIds.clear();
            if (pollingInterval) clearInterval(pollingInterval);
            fetchMessages(userId);
            pollingInterval = setInterval(() => fetchMessages(userId), 3000);
        }

        // ── Ambil & Tampilkan Pesan (tanpa kedip) ─────────────────────────
        function fetchMessages(userId) {
            fetch(`/messages/${userId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil pesan');
                    return res.json();
                })
                .then(messages => {
                    const display = document.getElementById('messageDisplay');

                    // Jika belum ada pesan yang ditampilkan (set kosong), ini pertama kali
                    if (displayedMessageIds.size === 0) {
                        display.innerHTML = ''; // bersihkan placeholder lama
                        if (!messages || messages.length === 0) {
                            display.innerHTML = '<div style="text-align:center;color:var(--chat-text-muted);padding:40px;">No messages yet. Say hello! 👋</div>';
                            return;
                        }
                        // Tampilkan semua pesan
                        messages.forEach(msg => {
                            appendMessage(msg, display);
                            displayedMessageIds.add(msg.id);
                        });
                    } else {
                        // Polling berikutnya: hanya tambahkan pesan yang ID-nya belum ada
                        const newMessages = messages.filter(msg => !displayedMessageIds.has(msg.id));
                        newMessages.forEach(msg => {
                            appendMessage(msg, display);
                            displayedMessageIds.add(msg.id);
                        });
                    }

                    // Auto-scroll ke bawah jika pengguna berada di bawah
                    const isAtBottom = display.scrollHeight - display.clientHeight <= display.scrollTop + 50;
                    if (isAtBottom) {
                        display.scrollTop = display.scrollHeight;
                    }
                })
                .catch(err => {
                    console.error('fetchMessages error:', err);
                    showError('⚠️ Gagal memuat pesan. Pastikan key enkripsi tersedia.');
                });
        }

        // ── Kirim Pesan ────────────────────────────────────────────────────
        function sendMessage(event) {
            event.preventDefault();

            const receiverId = document.getElementById('receiver_id').value;
            if (!receiverId) {
                alert('Pilih kontak terlebih dahulu.');
                return;
            }

            const msgInput = document.getElementById('msgInput');
            const formData = new FormData(document.getElementById('messageForm'));

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
                // Setelah kirim, langsung fetch ulang (polling akan menambahkan pesan baru tanpa kedip)
                fetchMessages(receiverId);
            })
            .catch(err => {
                console.error('sendMessage error:', err);
                showError('⚠️ Gagal mengirim pesan. Coba lagi.');
            });
        }

        // ── Observer untuk fallback auto-scroll (hanya untuk elemen baru) ──
        (function() {
            const msgDisplay = document.getElementById('messageDisplay');
            if (!msgDisplay) return;
            const observer = new MutationObserver(() => {
                // Scroll hanya jika pengguna berada di dekat bawah
                const isNearBottom = msgDisplay.scrollHeight - msgDisplay.clientHeight <= msgDisplay.scrollTop + 60;
                if (isNearBottom) {
                    msgDisplay.scrollTop = msgDisplay.scrollHeight;
                }
            });
            observer.observe(msgDisplay, { childList: true });
        })();
    </script>
</body>
</html>