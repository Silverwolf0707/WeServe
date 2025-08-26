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

    body {
      background: url('{{ asset('municipal.jpg') }}') no-repeat center center/cover;
      position: relative;
      height: 100vh;
      width: 100%;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.3);
      z-index: 0;
    }

    .container {
      display: flex;
      justify-content: flex-end;
      height: 100vh;
      width: 100%;
      position: relative;
      z-index: 1;
    }

    .right-panel {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-left: 1px solid rgba(255, 255, 255, 0.3);
      display: flex;
      justify-content: center;
      align-items: center;
      width: 30%;
      height: 100vh;   /* 🔥 full height */
      padding: 2rem;
      z-index: 2;
    }

    .login-wrapper {
      width: 100%;
      max-width: 400px;
      padding: 20px;
    }

    .login-wrapper h2 {
      text-align: center;
      color: #fff;
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
      color: #fff;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 12px 12px 12px 40px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: rgba(255, 255, 255, 0.9);
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
      color: #fff;
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
      color: #fff;
      font-size: 0.9rem;
    }

    .forgot-password a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .container {
        justify-content: center;
      }

      .right-panel {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="right-panel">
      <div class="login-wrapper">
        <h2>LOGIN</h2>

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