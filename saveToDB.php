<?php
session_start();
include("dbConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_order_price = $_POST['total_order_price'];
    $total_discounted = $_POST['total_discounted'];
    $payment_method = $_POST['payment_method'];
    $dining_method = $_POST['dining_method'];
    $cart = isset($_POST['cart']) ? json_decode($_POST['cart'], true) : [];

    $conn->begin_transaction();

    try {
        // Insert sale record
        $sql = "INSERT INTO salestbl (TotalRaw, TotalDiscounted, PaymentMethod, DiningMethod, SaleDate)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ddss", $total_order_price, $total_discounted, $payment_method, $dining_method);
        $stmt->execute();
        $sales_id = $conn->insert_id;

        // Prepare order insert statement
        $order_sql = "INSERT INTO ordertbl (SalesID, MenuID, qty) VALUES (?, ?, ?)";
        $order_stmt = $conn->prepare($order_sql);

        foreach ($cart as $item) {
            if ($item['quantity'] > 0 && !empty($item['name'])) {
                $name = $conn->real_escape_string($item['name']);
                $menu_query = "SELECT MenuID FROM menutbl WHERE LOWER(ItemName) = LOWER(?) LIMIT 1";
                $menu_stmt = $conn->prepare($menu_query);
                $menu_stmt->bind_param("s", $name);
                $menu_stmt->execute();
                $menu_result = $menu_stmt->get_result();
                
        
                if ($menu_result && $menu_result->num_rows > 0) {
                    $row = $menu_result->fetch_assoc();
                    $menu_id = $row['MenuID'];
                    $qty = $item['quantity'];
                
                    $order_stmt->bind_param("iii", $sales_id, $menu_id, $qty);
                    $order_stmt->execute();
                } else {
                    throw new Exception("Menu item '{$item['name']}' not found in menutbl.");
                }
                
            }
        }
        

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Sale and orders saved successfully."]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Transaction failed: " . $e->getMessage()]);
    }

    $stmt->close();
    $order_stmt->close();
    $conn->close();
}
?>
