<?php
// Enable error reporting (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $expiry      = $_POST['expiry_datetime'] ?? '';
    $category    = $_POST['category'] ?? '';
    $department  = trim($_POST['department'] ?? '');

    // Simple validation
    if ($title && $description && $expiry && $category && $department) {
        // Insert into PostgreSQL using prepared statement
        $query = "INSERT INTO notice (title, description, expiry_datetime, category, department) 
                  VALUES ($1, $2, $3, $4, $5)";

        $result = pg_query_params($conn, $query, [$title, $description, $expiry, $category, $department]);

        if ($result) {
            $message = "✅ Notice created successfully!";
        } else {
            $message = "❌ Error creating notice: " . pg_last_error($conn);
        }
    } else {
        $message = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Notice</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; font-family:'Montserrat',sans-serif; background: linear-gradient(120deg, #89f7fe, #66a6ff);}
        body { display:flex; justify-content:center; align-items:center; }
        .container { width:90%; max-width:600px; background:white; border-radius:12px; box-shadow:0 6px 12px rgba(0,0,0,0.1); padding:20px; text-align:center;}
        h2 { font-size:1.8em; margin-bottom:15px; color:#333;}
        form { display:flex; flex-direction:column; gap:15px; }
        label { text-align:left; font-weight:bold; color:#555;}
        input, textarea, select, button { width:100%; padding:10px; font-size:16px; border:1px solid #ccc; border-radius:5px;}
        textarea { resize:none; }
        button { background:#6c63ff; color:white; font-size:16px; cursor:pointer; border:none; transition:background 0.3s;}
        button:hover { background:#5753c9;}
        .message { margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Notice</h2>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="teacher_dashboard.php" method="POST">
            <label>Title:</label>
            <input type="text" name="title" required>

            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>

            <label>Expiry Date and Time:</label>
            <input type="datetime-local" name="expiry_datetime" required>

            <label>Category:</label>
            <select name="category" required>
                <option value="">--Select Category--</option>
                <option value="1">Academic</option>
                <option value="2">Sports</option>
                <option value="3">Events</option>
                <option value="4">Exams</option>
                <option value="5">Scholarship</option>
            </select>

            <label>Department:</label>
            <input type="text" name="department" required>

            <button type="submit">Create Notice</button>
        </form>
    </div>
</body>
</html>
