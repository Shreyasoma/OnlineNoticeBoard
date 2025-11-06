<?php
// Detect the correct session name dynamically
if (isset($_COOKIE['student_session'])) {
    session_name('student_session');
} elseif (isset($_COOKIE['teacher_session'])) {
    session_name('teacher_session');
} elseif (isset($_COOKIE['admin_session'])) {
    session_name('admin_session');
} else {
    // No valid session found, redirect to login
    echo "<script>alert('Session expired. Please log in again.'); window.location.href = 'index.html';</script>";
    exit;
}

// Start the session after detecting the session name
session_start();

// Validate if the session has required variables
if (!isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    echo "<script>alert('Session expired or invalid. Please log in again.'); window.location.href = 'index.html';</script>";
    exit;
}
// Database connection
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;

$conn = pg_connect("host=$host dbname=$dbname user=$username password=$password port=$port");

if (!$conn) {
    die("Database connection failed: " . pg_last_error());
}

// Fetch role-based notices
$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$department = '';

if ($user_role === 'student') {
    $query = "SELECT student_department FROM student WHERE user_id = $1";
} elseif ($user_role === 'teacher') {
    $query = "SELECT teacher_department FROM teacher WHERE user_id = $1";
} elseif ($user_role === 'admin') {
    $query = "SELECT admin_department FROM admin WHERE user_id = $1";
}

if (!empty($query)) {
    $result = pg_query_params($conn, $query, [$user_id]);
    if ($result && pg_num_rows($result) > 0) {
        $department = pg_fetch_result($result, 0, 0);
    }
}

// Fetch notices
if ($user_role === 'admin') {
    $query = "SELECT notice.*, category.name AS category_name, category.color_code AS category_color 
              FROM notice 
              LEFT JOIN category ON notice.category_id = category.id 
              WHERE status = 'approved' 
              AND expiry_datetime >= NOW() 
              ORDER BY date DESC";
    $result = pg_query($conn, $query);
} else {
    $query = "SELECT notice.*, category.name AS category_name, category.color_code AS category_color 
              FROM notice 
              LEFT JOIN category ON notice.category_id = category.id 
              WHERE status = 'approved' 
              AND expiry_datetime >= NOW() 
              AND (department ILIKE 'General' OR department ILIKE $1)
              ORDER BY date DESC";
    $result = pg_query_params($conn, $query, [$department]);
}

if (!$result) {
    die("Error fetching notices: " . pg_last_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Notices</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('https://www.transparenttextures.com/patterns/diamond-upholstery-dark.png'), linear-gradient(120deg, #84fab0, #8fd3f4);
            text-align: center;
        }
        .notice-card {
            background-color: white;
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Notices</h2>
    <?php if ($result && pg_num_rows($result) > 0) { ?>
        <?php while ($row = pg_fetch_assoc($result)) { ?>
            <div class="notice-card" style="background-color: <?php echo htmlspecialchars($row['category_color']); ?>;">
                <p><strong>Title:</strong> <?php echo htmlspecialchars($row['title']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category_name']); ?></p>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No notices available.</p>
    <?php } ?>
</body>
</html>
