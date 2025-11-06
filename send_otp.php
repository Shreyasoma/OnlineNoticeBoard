<?php include 'config.php'; ?>

<?php
session_start();
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;

// Connect to the PostgreSQL database
$conn = pg_connect("host=$host dbname=$dbname user=$username password=$password port=$port");

if (!$conn) {
    die("Database connection failed: " . pg_last_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = pg_escape_string($_POST['email']);

    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = pg_query($conn, $query);

    if (pg_num_rows($result) > 0) {
        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_expiry'] = time() + (5 * 60); // Current time + 5 minutes
        $_SESSION['email'] = $email;

        // Send OTP to user's email
        $subject = "Password Reset OTP";
        $message = "Your OTP for resetting the password is: $otp\nThis OTP is valid for 5 minutes.";
        $headers = "From: noreply@example.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "<script>alert('OTP sent successfully!'); window.location.href = 'verify_otp.php';</script>";
        } else {
            echo "<script>alert('Failed to send OTP. Please try again.'); window.location.href = 'forget_password.html';</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please try again.'); window.location.href = 'forget_password.html';</script>";
    }
}
?>
