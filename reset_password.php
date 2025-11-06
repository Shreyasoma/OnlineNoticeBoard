<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: "Montserrat", sans-serif;
      background: url("https://www.transparenttextures.com/patterns/diamond-upholstery-dark.png"),
        linear-gradient(120deg, #89f7fe, #66a6ff);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    .container {
      width: 90%;
      max-width: 600px;
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
      text-align: center;
    }
    h2 {
      font-size: 2.5em;
      color: #333;
    }
    label {
      font-weight: bold;
      margin-top: 15px;
      color: #333;
      display: block;
      text-align: left;
    }
    input,
    button {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      margin-top: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    input {
      background: #f4f4f4;
      color: #333;
    }
    button {
      background: #6c63ff;
      color: #fff;
      font-size: 18px;
      cursor: pointer;
    }
    button:hover {
      background: #5753c9;
    }
    .toggle-link {
      cursor: pointer;
      color: #6c63ff;
      font-size: 14px;
      text-decoration: underline;
    }
  </style>
  <script>
    function togglePasswordVisibility(id, toggleLinkId) {
      const passwordField = document.getElementById(id);
      const toggleLink = document.getElementById(toggleLinkId);
      if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleLink.textContent = "Hide";
      } else {
        passwordField.type = "password";
        toggleLink.textContent = "Show";
      }
    }
  </script>
</head>
<body>
  <div class="container">
    <h2>Reset Password</h2>
    <form action="update_password.php" method="POST">
      <label for="new_password">Enter New Password:</label>
      <div class="password-container">
        <input
          type="password"
          id="new_password"
          name="new_password"
          placeholder="Enter your password"
          required
        />
        <span
          id="toggle-password"
          class="toggle-link"
          onclick="togglePasswordVisibility('new_password', 'toggle-password')"
          >Show</span
        >
      </div>

      <label for="confirm_password">Confirm Password</label>
      <div class="password-container">
        <input
          type="password"
          id="confirm_password"
          name="confirm_password"
          placeholder="Re-enter your password"
          required
        />
        <span
          id="toggle-confirm-password"
          class="toggle-link"
          onclick="togglePasswordVisibility('confirm_password', 'toggle-confirm-password')"
          >Show</span
        >
      </div>
      
      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
