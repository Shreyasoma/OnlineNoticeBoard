<?php include 'config.php'; ?>

<?php
session_name('student_session'); // Ensure session consistency
session_start();

// Check if student_id exists instead of role
if (!isset($_SESSION['student_id'])) {
    echo "<script>alert('You must be logged in as a student to access this page.'); window.location.href = 'student_login.html';</script>";
    exit;
}

// Database connection
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;

$conn = pg_connect("host=$host dbname=$dbname user=$username port=$port password=$password");

if (!$conn) {
    echo "<script>alert('Database connection failed!');</script>";
    exit;
}

// Fetch all approved notices, excluding expired ones
$query = "
    SELECT notice.*, category.name AS category_name, category.color_code AS category_color
    FROM notice 
    LEFT JOIN category ON notice.category_id = category.id 
    WHERE notice.status = 'approved' 
      AND notice.expiry_datetime >= NOW() 
    ORDER BY notice.date DESC";

$result = pg_query($conn, $query);

if (!$result) {
    echo "<script>alert('Error fetching notices.');</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('https://www.transparenttextures.com/patterns/diamond-upholstery-dark.png'), linear-gradient(120deg, #f6d365, #fda085);
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
            margin-left: 10px;
        }
        .navbar a:hover {
            background-color: #5753c9;
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
            text-align: center;
        }
        td {
            text-align: center;
        }
        .category {
            font-weight: bold;
            padding: 5px;
            color: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Student Dashboard</h1>
        <div>
            <a href="view_notice.php">View Notices</a>
            <a href="index.html">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>All Notices</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Expiry Date</th>
                <th>Category</th>
                <th>Attachment</th>
            </tr>
            <?php if (pg_num_rows($result) > 0) { ?>
                <?php while ($row = pg_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['expiry_datetime']); ?></td>
                        <td>
                            <span class="category" style="background-color: <?php echo htmlspecialchars($row['category_color']); ?>;">
                                <?php echo isset($row['category_name']) ? htmlspecialchars($row['category_name']) : 'No Category'; ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!empty($row['attachment'])) { ?>
                                <a href="view_attachment.php?file=<?php echo htmlspecialchars($row['attachment']); ?>" target="_blank">View Attachment</a>
                            <?php } else { ?>
                                No attachment
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6">No notices available.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>

<?php
// Close PostgreSQL connection at the end
pg_close($conn);
?>
