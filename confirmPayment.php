<?php
include("dbConnect.php");

// Check if the POST request contains the necessary information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["paymentMethod"])) {
    // Fetch the necessary data from the POST request
    $paymentMethod = $_POST["paymentMethod"];
    $salesID = $_POST["salesID"];
    $totalRaw = $_POST["totalRaw"];
    $totalDiscount = $_POST["totalDiscount"];
    $diningMethod = $_POST["diningMethod"];
    
    // Get the current date for salesDate
    $salesDate = date('Y-m-d H:i:s');

    // Prepare the SQL query to insert the record into salestbl
    $insertQuery = "INSERT INTO salestbl (SalesID, TotalRaw, TotalDiscount, PaymentMethod, DiningMethod, salesDate) 
                    VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("iidsss", $salesID, $totalRaw, $totalDiscount, $paymentMethod, $diningMethod, $salesDate);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Payment confirmed and record inserted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to insert payment record."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    exit();
} else {
    // If necessary data is not provided, return an error message
    echo json_encode(["success" => false, "message" => "Missing payment method or required data."]);
}
?>