<?php
$host = "dpg-d46a93chg0os73eeqdt0-a.oregon-postgres.render.com";
$dbname = "online_noticeboard_db";
$user = "online_noticeboard_db_user";
$password = "9RwGmBUS7wkhBWU3XG8MBqOybxzOrGkt";
$port = "5432";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // optional test line
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
