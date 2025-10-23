<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WeServe Login</title>
  <link rel="icon" href="{{ asset('WeServe Logo.png') }}" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #e8f5e9;
      overflow: hidden;
    }

    .background {
      position: absolute;
      inset: 0;
      z-index: 0;
    }

    .background-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .background-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom right, rgba(11, 83, 45, 0.85), rgba(0, 0, 0, 0.6));
      backdrop-filter: blur(6px);
    }

    .content {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 460px;
      animation: fadeUp 0.7s ease forwards;
    }

    @keyframes fadeUp {
      0% {
        transform: translateY(15px);
        opacity: 0;
      }

      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .login-card {
      backdrop-filter: blur(20px);
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
    }

    .login-card:hover {
      box-shadow: 0 0 50px rgba(46, 204, 113, 0.25);
    }

    .brand-section-inside {
      text-align: center;
      margin-bottom: 28px;
    }

    .logo-circle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 70px;
      height: 70px;
      border-radius: 100%;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.25);
      margin-bottom: 14px;
      box-shadow: 0 0 25px rgba(46, 204, 113, 0.3);
    }

    .brand-title {
      font-size: 34px;
      font-weight: 800;
      background: linear-gradient(135deg, #aef1c5, #2ecc71);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 6px;
      letter-spacing: 0.5px;
    }

    .brand-subtitle {
      font-size: 15px;
      color: rgba(255, 255, 255, 0.85);
      font-weight: 400;
    }

    .form-group {
      margin-bottom: 20px;
      animation: fadeUp 0.6s ease forwards;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 500;
      color: white;
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: gray;
      font-size: 15px;
    }

    .form-input {
      width: 100%;
      height: 48px;
      padding: 0 12px 0 40px;
      background: rgba(255, 255, 255, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 10px;
      color: #fff;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .form-input::placeholder {
      color: rgba(255, 255, 255, 0.3);
    }

    .form-input:focus {
      outline: none;
      border-color: #2ecc71;
      box-shadow: 0 0 10px rgba(46, 204, 113, 0.4);
      background: rgba(255, 255, 255, 0.25);
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: gray;
      transition: all 0.3s ease;
      font-size: 15px;
    }

    .toggle-password:hover {
      color: gray;
      transform: translateY(-50%) scale(1.1);
    }

    .forgot-password {
      text-align: right;
      margin-bottom: 14px;
    }

    .support-link {
      color: #aef1c5;
      font-weight: 500;
      text-decoration: none;
      transition: 0.3s;
    }

    .support-link:hover {
      text-decoration: underline;
    }

    .submit-button {
      width: 100%;
      height: 50px;
      margin-top: 10px;
      background: linear-gradient(135deg, #2ecc71, #27ae60);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
      transition: all 0.3s ease;
    }

    .submit-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(39, 174, 96, 0.5);
    }

    .card-footer {
      margin-top: 26px;
      padding-top: 20px;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      text-align: center;
    }

    .footer-text {
      font-size: 14px;
      color: rgba(255, 255, 255, 0.85);
    }

    .bottom-text {
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
      font-size: 12px;
      margin-top: 22px;
    }

    @media (max-width: 768px) {
      .login-card {
        padding: 30px;
      }
    }
  </style>
</head>

<body>
  <div class="background">
    <img src="{{ asset('municipal.jpg') }}" alt="Municipal Building" class="background-image" />
    <div class="background-overlay"></div>
  </div>

  <div class="content">
    <div class="login-card">
      <div class="brand-section-inside">
        <div class="logo-circle">
          <img src="{{ asset('WeServe Logo.png') }}" alt="WeServe Logo" width="42" height="42" />
        </div>
        <h1 class="brand-title">WeServe</h1>
        <p class="brand-subtitle">Financial Aid Management System</p>
      </div>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <div class="input-wrapper">
            <i class="fa fa-envelope input-icon"></i>
            <input type="email" id="email" name="email" class="form-input" placeholder="admin@example.com" required autofocus>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrapper">
            <i class="fa fa-lock input-icon"></i>
            <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required>
            <i class="fa-solid fa-eye toggle-password" id="togglePassword" title="Show Password"></i>
          </div>
        </div>

        <div class="forgot-password">
          <a href="{{ route('password.request') }}" class="support-link">Forgot your password?</a>
        </div>

        <button class="submit-button" type="submit">Sign In to Dashboard</button>
      </form>

      <div class="card-footer">
        <p class="footer-text">Need assistance? <a href="#" class="support-link">Contact IT Support</a></p>
      </div>
    </div>

    <p class="bottom-text">Secure government portal • Authorized access only</p>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", () => {
      const type = passwordField.type === "password" ? "text" : "password";
      passwordField.type = type;

   
      togglePassword.classList.toggle("fa-eye");
      togglePassword.classList.toggle("fa-eye-slash");


      togglePassword.title = type === "password" ? "Show Password" : "Hide Password";
    });
  </script>
</body>

</html>