<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forget Password</title>
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
    </style>
  </head>
  <body>
    <div class="container">
      <h2>Forget Password</h2>
      <form action="send_otp.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" autocomplete="off" placeholder="Enter your email" required />
        <button type="submit">Submit</button>
      </form>
    </div>
  </body>
</html>
