<?php
include("dbConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stars = isset($_POST['stars']) ? intval($_POST['stars']) : NULL;
    $comment = isset($_POST['comment']) && trim($_POST['comment']) !== "" ? trim($_POST['comment']) : NULL;

    if ($stars === NULL && $comment !== NULL) {
        echo "Error: Cannot submit a comment without a rating.";
        exit;
    }

    $query = "INSERT INTO ratingtbl (Stars, Comment) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $stars, $comment);

    if ($stmt->execute()) {
        echo "Feedback submitted successfully!";
    } else {
        echo "Error submitting feedback.";
    }

    $stmt->close();
    $conn->close();
}
?>
