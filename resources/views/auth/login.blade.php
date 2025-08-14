<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WeServe Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="icon" href="{{ asset('WeServe Logo.png') }}" type="image/x-icon" />

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        .left-panel {
            position: relative;
            flex: 1;
            background: url('{{ asset('municipal.jpg') }}') no-repeat center center/cover;
            z-index: 1;
        }

        .left-panel::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.3);

            z-index: 1;
        }


        .right-panel {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30%;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        /* Add a background behind the right panel to blur */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: lightblue;
            filter: brightness(0.9);
            z-index: 0;
        }

        .footer-image {
            width: 100%;
            /* take full width of right panel */
            margin-top: auto;
            /* push footer to bottom */
            margin-top: 1.5rem;
            /* optional spacing */
        }

        .footer-image img {
            width: 100%;
            /* image fills footer container */
            height: auto;
            /* keep aspect ratio */
            border-radius: 8px;
            /* optional styling */
            object-fit: contain;
            /* scale nicely */
        }


        .login-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 16px;
        }

        .logo {
            display: block;
            margin: 0 auto 10px;
            width: 60px;
        }

        .login-wrapper h2 {
            text-align: center;
            color: #333;
            font-size: 2.2rem;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f0f8f4;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: #2ebf59;
            box-shadow: 0 0 5px #2ebf59;
            outline: none;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #2ebf59;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #2ebf59;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 0.95rem;
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
            font-weight: bold;
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
            color: #007bff;
            font-size: 0.9rem;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                height: 50vh;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel"></div>
        <div class="right-panel">
            <div class="login-wrapper">
                <h2>LOGIN</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="admin@example.com" required
                                autofocus>
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
        const togglePassword = document.getElementById("togglePassword");
        const passwordField = document.getElementById("password");

        togglePassword.addEventListener("click", () => {
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);

            togglePassword.classList.toggle("fa-eye-slash");
            togglePassword.classList.toggle("fa-eye");
        });
    </script>
</body>

</html>