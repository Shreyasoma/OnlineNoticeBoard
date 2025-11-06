<?php include 'config.php'; ?>

<?php
session_name('student_session'); // Unique session for students
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
    $query = "SELECT * FROM users WHERE email = '$email' AND role = 'student'";
    $result = pg_query($conn, $query);

    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);

        //if (password_verify($password, $user['password'])) 
	if ($password === trim($user['password'])){
            // Fetch student details from student table
            $user_id = $user['user_id'];
            $student_query = "SELECT * FROM student WHERE user_id = '$user_id'";
            $student_result = pg_query($conn, $student_query);

            if (pg_num_rows($student_result) > 0) {
                $student = pg_fetch_assoc($student_result);

                // Store session variables
                $_SESSION['student_id'] = $student['student_id'];
                $_SESSION['role'] = 'student'; // Ensure role is set
                $_SESSION['user_id'] = $user['user_id']; // Ensure user_id is set
                $_SESSION['student_name'] = trim($user['name']); // Trim to remove extra spaces from CHAR type

                header("Location: student_dashboard.php"); // Redirect after successful login
                exit();
            } else {
                echo "<script>alert('Student details not found.'); window.location.href = 'student_login.html';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password'); window.location.href = 'student_login.html';</script>";
        }
    } else {
        echo "<script>alert('Email not found or not a student'); window.location.href = 'student_login.html';</script>";
    }
}
?>
