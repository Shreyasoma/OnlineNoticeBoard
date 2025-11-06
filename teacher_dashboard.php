<?php include 'config.php'; ?>

<?php
session_name('teacher_session'); // Use the correct session
session_start();

if (!isset($_SESSION['teacher_id'])) {
    echo "<script>alert('You must be logged in as a teacher to access this page.'); window.location.href = 'teacher_login.html';</script>";
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;$conn = pg_connect("host=$host dbname=$dbname user=$username port=$port password=$password");

if (!$conn) {
   die("Connection failed: " . pg_last_error());
}

// Fetch only active (non-expired) notices for teachers
$query = "
    SELECT notice.*, category.name AS category_name, category.color_code AS category_color
    FROM notice 
    LEFT JOIN category ON notice.category_id = category.id 
    WHERE expiry_datetime >= NOW()  -- Only fetch active notices
    ORDER BY notice.date DESC";
$result = pg_query($conn, $query);

pg_close($conn); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('https://www.transparenttextures.com/patterns/diamond-upholstery-dark.png'), linear-gradient(120deg, #84fab0, #8fd3f4);
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .navbar {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 1.5em;
            margin: 0;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #6c63ff;
            border-radius: 5px;
            margin-left: 10px; /* Add some spacing between buttons */
        }
        .navbar a:hover {
            background-color: #5753c9;
        }
        .create-section {
            display: flex;
            justify-content: center;
            margin: 30px 0;
        }
        .btn-create {
            padding: 10px 20px;
            background-color: #ff5733;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-create:hover {
            background-color: #c70039;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin: 30px 0;
            padding: 30px;
        }
        h2 {
            font-size: 2em;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #6c63ff;
            color: white;
        }
        .category {
            font-weight: bold;
            padding: 5px;
            color: white;
            border-radius: 4px;
        }
        .btn {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Teacher Dashboard</h1>
        <div>
            <a href="view_notice.php">View Notices</a>
            <a href="index.html" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="create-section">
        <a href="create_notice.php" class="btn-create">Create Notice</a>
    </div>

    <div class="container">
        <h2>Manage Notices</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Expiry Date</th>
                <th>Status</th>
                <th>Attachment</th>
            </tr>
            <?php while ($row = pg_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <span class="category" style="background-color: <?php echo htmlspecialchars($row['category_color']); ?>;">
                            <?php echo isset($row['category_name']) ? htmlspecialchars($row['category_name']) : 'No Category'; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row['expiry_datetime']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php if (!empty($row['attachment'])) { ?>
                            <a href="view_attachment.php?file=<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank">View</a>
                        <?php } else { ?>
                            No attachment
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
