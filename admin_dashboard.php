<?php include 'config.php'; ?>

<?php
session_name('admin_session'); // Use the correct session
session_start();

if (empty($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    session_destroy();
    header("Location: admin_login.html");
    exit();
}


// Database connection
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;
$conn = pg_connect("host=$host dbname=$dbname user=$username port=$port password=$password");

if (!$conn) {
    echo "<script>alert('Connection failed: " . pg_last_error() . "');</script>";
    exit;
}

// Automatically archive expired notices
$archive_query = "INSERT INTO archive (title, description, n_department, status, date, expiry_datetime, created_by, category_id, attachment)
                  SELECT title, description, department, 'archived', date, expiry_datetime, created_by, category_id, attachment
                  FROM notice WHERE expiry_datetime < now() AND status != 'archived'";

$archive_result = pg_query($conn, $archive_query);

if (!$archive_result) {
    echo "<script>alert('Error archiving expired notices: " . pg_last_error($conn) . "');</script>";
}

// Update status of archived notices
$update_status_query = "UPDATE notice SET status = 'archived' WHERE expiry_datetime < now() AND status != 'archived'";
$update_status_result = pg_query($conn, $update_status_query);

if (!$update_status_result) {
    echo "<script>alert('Error updating notice status: " . pg_last_error($conn) . "');</script>";
}

// Handle form submission for approval or deletion of notices
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $notice_id = $_POST['notice_id'];
    if ($_POST['action'] == 'approve') {
        $approve_query = "UPDATE notice SET status = 'approved' WHERE notice_id = $1";
        $result_approve = pg_query_params($conn, $approve_query, [$notice_id]);
        if ($result_approve) {
            echo "<script>alert('Notice approved');</script>";
        } else {
            echo "<script>alert('Error approving notice: " . pg_last_error($conn) . "');</script>";
        }
    } elseif ($_POST['action'] == 'delete') {
        $delete_query = "UPDATE notice SET status = 'deleted' WHERE notice_id = $1";
        $result_delete = pg_query_params($conn, $delete_query, [$notice_id]);
        if ($result_delete) {
            echo "<script>alert('Notice deleted');</script>";
        } else {
            echo "<script>alert('Error deleting notice: " . pg_last_error($conn) . "');</script>";
        }
    }
    echo "<script>window.location.href = 'admin_dashboard.php';</script>";
    exit;
}

// Fetch active notices
$query = "SELECT n.notice_id, n.title, n.description, n.expiry_datetime, n.status, n.attachment, c.name, c.color_code 
          FROM notice n
          LEFT JOIN category c ON n.category_id = c.id
          WHERE n.status != 'archived'
          ORDER BY expiry_datetime DESC";

$result = pg_query($conn, $query);

if (!$result) {
    die("Error fetching notices: " . pg_last_error($conn));
}

// Fetch archived notices
$query_archived = "SELECT a.title, a.description, a.expiry_datetime, a.status, a.attachment, c.name, c.color_code
                   FROM archive a
                   LEFT JOIN category c ON a.category_id = c.id
                   ORDER BY expiry_datetime DESC";

$result_archived = pg_query($conn, $query_archived);

if (!$result_archived) {
    die("Error fetching archived notices: " . pg_last_error($conn));
}

pg_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('https://www.transparenttextures.com/patterns/diamond-upholstery-dark.png'), linear-gradient(120deg, #89f7fe, #66a6ff);
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
        .btn {
            padding: 5px 10px;
            background-color: #6c63ff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #5753c9;
        }
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #e53935;
        }
        .category {
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <div>
            <a href="view_notice.php">View Notices</a>
            <a href="index.html">Logout</a>
        </div>
    </div>

    <div class="container">
    <h2>Active Notices</h2>
    <table>
        <tr><th>Title</th><th>Description</th><th>Category</th><th>Expiry Date</th><th>Status</th><th>Attachment</th><th>Actions</th></tr>
        <?php while ($row = pg_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><span class="category" style="background-color: <?= htmlspecialchars($row['color_code']) ?>;">
                    <?= htmlspecialchars($row['name'] ?? 'No Category') ?></span></td>
                <td><?= htmlspecialchars($row['expiry_datetime']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <?php if (!empty($row['attachment'])) { ?>
                        <a href="uploads/<?= htmlspecialchars($row['attachment']) ?>" target="_blank">View</a>
                    <?php } else { echo "No attachment"; } ?>
                </td>
                <td>
                    <?php if ($row['status'] == 'pending') { ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="notice_id" value="<?= $row['notice_id'] ?>">
                            <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
                            <button type="submit" name="action" value="delete" class="btn btn-delete">Delete</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<div class="container">
    <h2>Archived Notices</h2>
    <table>
        <tr><th>Title</th><th>Description</th><th>Category</th><th>Expiry Date</th><th>Status</th><th>Attachment</th></tr>
        <?php while ($row = pg_fetch_assoc($result_archived)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><span class="category" style="background-color: <?= htmlspecialchars($row['color_code']) ?>;">
                    <?= htmlspecialchars($row['name'] ?? 'No Category') ?></span></td>
                <td><?= htmlspecialchars($row['expiry_datetime']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <?php if (!empty($row['attachment'])) { ?>
                        <a href="uploads/<?= htmlspecialchars($row['attachment']) ?>" target="_blank">View</a>
                    <?php } else { echo "No attachment"; } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
