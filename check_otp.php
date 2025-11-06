<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    // Check if OTP is expired
    if (time() > $_SESSION['otp_expiry']) {
        unset($_SESSION['otp']); // Remove expired OTP
        unset($_SESSION['otp_expiry']);
        echo "<script>alert('OTP has expired. Please request a new one.'); window.location.href = 'forget_password.html';</script>";
        exit();
    }

    // Validate OTP
    if ($entered_otp == $_SESSION['otp']) {
        echo "<script>alert('OTP verified successfully!'); window.location.href = 'reset_password.php';</script>";
    } else {
        echo "<script>alert('Invalid OTP. Please try again.'); window.location.href = 'verify_otp.php';</script>";
    }
}
?>
