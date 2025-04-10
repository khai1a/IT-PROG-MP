<?php
include("dbConnect.php");

if (isset($_POST['itemId'])) {
    $itemId = $_POST['itemId'];

    $query = "SELECT ItemDescription FROM menutbl WHERE MenuID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $stmt->bind_result($conversation);
    $stmt->fetch();
    $stmt->close();

    echo $conversation ? $conversation : "Wala akong masasabi dyan bhie...";
}
?>
