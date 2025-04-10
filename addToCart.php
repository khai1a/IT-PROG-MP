<?php
session_start();
include("dbConnect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];
    $itemName = $_POST['itemName'];
    $quantity = intval($_POST['quantity']);
    
    $query = "SELECT Price FROM MenuTbl WHERE MenuID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $stmt->bind_result($itemPrice);
    $stmt->fetch();
    $stmt->close();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $itemId) {
            $item['quantity'] = $quantity;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $itemId,
            'name' => $itemName,
            'quantity' => $quantity,
            'price' => $itemPrice
        ];
    }

    echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
}
?>
