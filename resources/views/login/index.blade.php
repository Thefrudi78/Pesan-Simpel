<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login - SimpleChat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, system-ui, -apple-system, 'Helvetica Neue', sans-serif;
            /* Warna primer #2c3e50 diaplikasikan pada gradien background badan untuk keselarasan */
            background: linear-gradient(135deg, #2c3e50 0%, #1a2632 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Login container responsif */
        .login-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #ffffff;
            border-radius: 32px;
            box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.25), 0 8px 18px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 460px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .login-header {
            text-align: center;
            padding: 2rem 1.5rem 0.5rem 1.5rem;
        }

        /* Logo styling */
        .logo-area {
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-img {
            max-width: 180px;
            width: 70%;
            height: auto;
            object-fit: contain;
            display: block;
            transition: transform 0.2s ease;
        }

        .login-header p {
            color: #6c757d;
            font-size: 0.95rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .login-body {
            padding: 1.5rem 2rem 2rem 2rem;
        }

        /* Error message */
        .error-message {
            background-color: #fee2e2;
            border-left: 4px solid #e74c3c;
            color: #b91c1c;
            padding: 0.85rem 1rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            font-weight: 500;
            word-break: break-word;
        }

        /* Form group */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1f2937;
            font-size: 0.9rem;
            letter-spacing: -0.2px;
        }

        .form-group input {
            width: 100%;
            padding: 0.9rem 1rem;
            font-size: 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            background-color: #fefefe;
            transition: all 0.2s ease;
            font-family: inherit;
            outline: none;
        }

        /* Warna primer #2c3e50 untuk efek focus */
        .form-group input:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.2);
        }

        /* Tombol login dengan warna primer solid #2c3e50 */
        .login-btn {
            width: 100%;
            background: #2c3e50;  /* Warna utama sesuai permintaan */
            border: none;
            padding: 0.9rem 1rem;
            border-radius: 40px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
            letter-spacing: 0.5px;
        }

        .login-btn:hover {
            background: #1e2b38;  /* versi lebih gelap dari #2c3e50 */
            transform: scale(1.01);
            box-shadow: 0 10px 20px -5px rgba(44, 62, 80, 0.4);
        }

        .login-btn:active {
            transform: scale(0.98);
        }

        /* Demo info area */
        .demo-info {
            margin-top: 2rem;
            background: #f8fafc;
            padding: 0.9rem 1rem;
            border-radius: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #334155;
            border: 1px solid #e9edf2;
        }

        .demo-info p {
            margin: 0.2rem 0;
        }

        .demo-info p:first-child {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #2c3e50;  /* sentuhan warna primer pada teks demo */
        }

        /* ========== RESPONSIVE (Mobile & Tablet) ========== */
        @media (max-width: 640px) {
            body {
                padding: 0.75rem;
            }
            .login-card {
                max-width: 100%;
                border-radius: 28px;
            }
            .login-body {
                padding: 1.25rem 1.5rem 1.8rem 1.5rem;
            }
            .login-header {
                padding: 1.5rem 1.2rem 0.2rem 1.2rem;
            }
            .logo-img {
                max-width: 140px;
                width: 60%;
            }
            .form-group input {
                padding: 0.8rem 0.9rem;
                font-size: 0.95rem;
            }
            .login-btn {
                padding: 0.8rem;
                font-size: 0.95rem;
            }
            .demo-info {
                font-size: 0.7rem;
                padding: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .login-body {
                padding: 1rem 1.2rem 1.5rem 1.2rem;
            }
            .login-header {
                padding: 1.2rem 1rem 0rem 1rem;
            }
            .logo-img {
                max-width: 120px;
                width: 55%;
            }
            .form-group label {
                font-size: 0.85rem;
            }
            .form-group input {
                padding: 0.7rem 0.85rem;
                border-radius: 18px;
            }
            .error-message {
                font-size: 0.75rem;
                padding: 0.7rem;
            }
            .login-btn {
                border-radius: 36px;
            }
        }

        @media (max-width: 380px) {
            .logo-img {
                max-width: 100px;
                width: 60%;
            }
            .login-body {
                padding: 0.8rem 1rem 1.2rem 1rem;
            }
            .demo-info {
                margin-top: 1.5rem;
            }
        }

        /* Touch improvements */
        .login-btn, .form-group input {
            -webkit-tap-highlight-color: transparent;
        }

        input[type="email"], input[type="password"] {
            -webkit-appearance: none;
            appearance: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <!-- Logo dari folder public/images/Logo.png -->
                <div class="logo-area">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo Perusahaan" class="logo-img">
                </div>
                <p>Login to continue</p>
            </div>

            <div class="login-body">
                @if($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="login-btn">Login</button>
                </form>

            
            </div>
        </div>
    </div>
</body>
</html>