<?php
if (!isset($_GET['file'])) {
    echo "<script>alert('No attachment found.'); window.location.href = 'student_dashboard.php';</script>";
    exit;
}

$file = htmlspecialchars($_GET['file']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attachment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header a {
            color: white;
            text-decoration: none;
            font-size: 24px;
        }
        .attachment {
            width: 100%;
            height: calc(100vh - 50px);
            border: none;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Attachment</h1>
        <a href="student_dashboard.php">&times;</a>
    </div>

    <iframe class="attachment" src="uploads/<?php echo $file; ?>" frameborder="0"></iframe>

</body>
</html>
