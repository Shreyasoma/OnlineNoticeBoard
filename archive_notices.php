<?php include 'config.php'; ?>

<?php
$host = 'localhost';
$dbname = 'postgres';
$username = 'postgres';
$password = 'Shreya@1410';
$port = 5432;
$conn = pg_connect("host=$host dbname=$dbname user=$username port=$port password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Move expired notices to archive
$query = "INSERT INTO archive (title, description, n_department, status, date, expiry_date)
          SELECT title, description, n_department, status, date, expiry_date 
          FROM notice 
          WHERE expiry_date < NOW()";

$result = pg_query($conn, $query);

if ($result) {
    // Delete expired notices from notice table
    pg_query($conn, "DELETE FROM notice WHERE expiry_date < NOW()");
}

pg_close($conn);
?>
