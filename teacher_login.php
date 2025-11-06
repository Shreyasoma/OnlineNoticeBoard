<?php
session_name('teacher_session'); // Unique session for students
session_start();

// Database connection
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

    // Check if the user exists and is a student
    $query = "SELECT * FROM users WHERE email = '$email' AND role = 'teacher'";
    $result = pg_query($conn, $query);

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        //if (password_verify($password, $user['password'])) 
	if ($password === trim($user['password'])){
            // Fetch student details from student table
            $user_id = $user['user_id'];
            $teacher_query = "SELECT * FROM teacher WHERE user_id = '$user_id'";
            $teacher_result = pg_query($conn, $teacher_query);

            if (pg_num_rows($teacher_result) > 0) {
                $teacher = pg_fetch_assoc($teacher_result);

                // Store session variables
                $_SESSION['teacher_id'] = $teacher['teacher_id'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['teacher_name'] = trim($user['name']);
                $_SESSION['role'] = 'teacher';

                header("Location: teacher_dashboard.php"); // Redirect after successful login
                exit();
            } else {
                echo "<script>alert('Teacher details not found.'); window.location.href = 'teacher_login.html';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password'); window.location.href = 'teacher_login.html';</script>";
        }
    } else {
        echo "<script>alert('Email not found or not a student'); window.location.href = 'teacher_login.html';</script>";
    }
}
?>
