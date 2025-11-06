<?php include 'config.php'; ?>

<?php
// Database connection details
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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Get the raw password
    $confirm_password = $_POST['confirm_password']; // Get the confirm password field
    $role = $_POST['role'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        echo "Error: Passwords do not match.";
    } else {
        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Begin transaction
        pg_query($conn, "BEGIN");

        try {
            // Insert into the `users` table (NO confirm_password field needed in the database)
            $query = "INSERT INTO users (name, email, password, role) VALUES ($1, $2, $3, $4) RETURNING user_id";
            $result = pg_query_params($conn, $query, [$name, $email, $hashed_password, $role]);

            if (!$result) {
                throw new Exception("Error inserting into users table: " . pg_last_error($conn));
            }

            // Get the last inserted user ID
            $row = pg_fetch_assoc($result);
            $userId = $row['user_id'];

            // Add role-specific data based on the role selected
            if ($role == 'student') {
                $student_roll_no = $_POST['student_roll_no'];
                $student_class = $_POST['student_class'];
                $student_department = $_POST['student_department'];
                $student_course = $_POST['course'];

                // Ensure roll_no and department are not empty before inserting
                if (!empty($student_roll_no) && !empty($student_department)) {
                    $query_student = "INSERT INTO student (user_id, student_roll_no, student_class, student_department, course) VALUES ($1, $2, $3, $4, $5)";
                    $result = pg_query_params($conn, $query_student, [$userId, $student_roll_no, $student_class, $student_department, $student_course]);

                    if (!$result) {
                        throw new Exception("Error inserting into student table: " . pg_last_error($conn));
                    }
                } else {
                    throw new Exception("Error: roll_no and department cannot be empty for students.");
                }
            } elseif ($role == 'teacher') {
                $teacher_emp_id = $_POST['teacher_emp_id'];
                $teacher_subject = $_POST['teacher_subject'];
                $teacher_department = $_POST['teacher_department'];

                // Ensure roll_no and department are not empty before inserting
                if (!empty($teacher_emp_id) && !empty($teacher_department)) {
                    $query_teacher = "INSERT INTO teacher (user_id, teacher_emp_id, teacher_subject, teacher_department) VALUES ($1, $2, $3, $4)";
                    $result = pg_query_params($conn, $query_teacher, [$userId, $teacher_emp_id, $teacher_subject, $teacher_department]);

                    if (!$result) {
                        throw new Exception("Error inserting into teacher table: " . pg_last_error($conn));
                    }
                } else {
                    throw new Exception("Error: roll_no and department cannot be empty for teachers.");
                }
            } elseif ($role == 'admin') {
                //$admin_username = $_POST['admin_username'];
                $admin_department = $_POST['admin_department'];

                // Insert admin data
                $query_admin = "INSERT INTO admin (user_id, admin_department) VALUES ($1, $2)";
                $result = pg_query_params($conn, $query_admin, [$userId, $admin_department]);

                if (!$result) {
                    throw new Exception("Error inserting into admin table: " . pg_last_error($conn));
                }
            }

            // Commit the transaction
            pg_query($conn, "COMMIT");

            // Redirect to login page after successful signup
            header("Location: login.html");
            exit;

        } catch (Exception $e) {
            // Rollback in case of an error
            pg_query($conn, "ROLLBACK");
            echo "Error: " . $e->getMessage();
        }
    }
}

// Close the connection
pg_close($conn);
?>