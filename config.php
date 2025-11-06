<?php
$dbhost = "localhost";
$dbname = "postgres";
$dbuser = "postgres";
$dbpass = "Shreya@1410";
$port = 5432;

$conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass port=$port");
if (!$conn) {
    die("Database connection failed.");
}
?>