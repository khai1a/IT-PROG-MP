<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "alingbebangdb";
$port = "3377";

$conn = new mysqli($servername,$username,$password,$database,$port);

if ($conn -> connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}
?>