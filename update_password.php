<?php
session_start();

$host = 'localhost';
$name = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;

$conn = pg_connect("host=$host dbname=$name user=$username password=$password port=$port");

if (!$conn) {
    die("Error: Unable to connect to the database.");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = pg_escape_string($_POST['new_password']);
    $confirm_password = pg_escape_string($_POST['confirm_password']);
    $email = $_SESSION['email'];

    if ($new_password == $confirm_password) {
        // Update password in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
        $result = pg_query($conn, $query);

        if ($result) {
            echo "<script>alert('Password updated successfully!'); window.location.href = 'login.html';</script>";
        } else {
            echo "<script>alert('Error updating password. Please try again.'); window.location.href = 'reset_password.php';</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match. Please try again.'); window.location.href = 'reset_password.php';</script>";
    }
}
?>
