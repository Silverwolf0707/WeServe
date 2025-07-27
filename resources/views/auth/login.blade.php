<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>WeServe Login</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .split-screen {
            display: flex;
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
        }

        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            background: linear-gradient(to bottom right, #0c7746, #a2e4a9);
            padding: 40px;
        }

        .left-panel h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .left-panel p {
            font-size: 1.25rem;
        }

        .right-panel {
            flex: 1;
            background-color: #f7fff9;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: radial-gradient(circle at center, #5CB338, #8FD694);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-card h2 {
            text-align: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .login-card p {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            padding-left: 40px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #e6f1ed;
        }

        .form-group .input-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #1e7a3a;
        }

        .form-group .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #1e7a3a;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #2ebf59;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-button:hover {
            background-color: #24a64a;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .split-screen {
                flex-direction: column;
            }

            .left-panel, .right-panel {
                flex: none;
                width: 100%;
                height: 50%;
            }

            .left-panel h1 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="split-screen">
        <!-- LEFT PANEL -->
        <div class="left-panel">
            <h1>WeServe</h1>
            <p>Your trusted financial assistance partner.</p>
        </div>

        <!-- RIGHT PANEL -->
        <div class="right-panel">
            <div class="login-card">
                <h2>WeServe</h2>
                <p>Login</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="admin@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>

                    <button class="login-button" type="submit">Login</button>

                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const passwordInput = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>