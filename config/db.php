<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chuamia_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>
