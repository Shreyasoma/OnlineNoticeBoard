<?php include 'config.php'; ?>

<?php
session_destroy();
session_name('admin_session'); // Unique session for admins
session_start();

$dbhost = "localhost";
$dbname = "postgres";
$dbuser = "postgres";
$dbpass = "Shreya@1410";
$port = 5432;

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass port=$port");
if (!$conn) {
    die("Database connection failed.");
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = pg_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Password should not be escaped for verification

    // Check if the user exists and is a admin
    $query = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";
    $result = pg_query($conn, $query);

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        //if (password_verify($password, $user['password'])) 
	if ($password === trim($user['password'])){
            // Fetch admin details from admin table
            $user_id = $user['user_id'];
            $admin_query = "SELECT * FROM admin WHERE user_id = '$user_id'";
            $admin_result = pg_query($conn, $admin_query);

            if (pg_num_rows($admin_result) > 0) {
                $admin = pg_fetch_assoc($admin_result);

                // Store session variables
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['admin_name'] = trim($user['name']); // Trim to remove extra spaces from CHAR type
                $_SESSION['role'] = 'admin';

                header("Location: admin_dashboard.php"); // Redirect after successful login
                exit();
            } else {
                echo "<script>alert('admin details not found.'); window.location.href = 'admin_login.html';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password'); window.location.href = 'admin_login.html';</script>";
        }
    } else {
        echo "<script>alert('Email not found or not a admin'); window.location.href = 'admin_login.html';</script>";
    }
}
?>
