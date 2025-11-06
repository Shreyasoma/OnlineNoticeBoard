<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP</title>
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
    p {
      margin-top: 20px;
    }
  </style>
  <script>
    function startTimer(duration, display) {
      var timer = duration, minutes, seconds;
      var interval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
          clearInterval(interval);
          alert("OTP has expired! Please request a new OTP.");
          window.location.href = "forget_password.html";
        }
      }, 1000);
    }

    window.onload = function () {
      var fiveMinutes = 60 * 5; // 5 minutes in seconds
      var display = document.querySelector('#timer');
      startTimer(fiveMinutes, display);
    };
  </script>
</head>
<body>
  <div class="container">
    <h2>Verify OTP</h2>
    <p>OTP expires in <span id="timer">05:00</span></p>
    <form action="check_otp.php" method="POST">
      <label for="otp">Enter OTP:</label>
      <input type="text" id="otp" name="otp" required>
      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
