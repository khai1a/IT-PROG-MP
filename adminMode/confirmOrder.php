<html>

<head>
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SalesID = $_POST['SalesID'];
        $ItemName = $_POST['ItemName'];
        $Qty = $_POST['Qty'];
    }
    include 'dbConnect.php';
    ?>
    <?php
    $MenuIDRes = $conn->query("SELECT MenuID, ItemName FROM menutbl WHERE ItemName = '" . $ItemName . "';");
    $MenuID = mysqli_fetch_array($MenuIDRes)['MenuID'];
    echo "Inputted SalesID: " . $SalesID;
    echo "<br>Inputted Item: " . $MenuID . " (" . $ItemName . ")";
    echo "<br>Inputted Quantity: " . $Qty;
    mysqli_query($conn, "INSERT INTO ordertbl (OrderID, SalesID, MenuID, qty) VALUES (NULL, " . $SalesID . ", " . $MenuID . ", " . $Qty . ");");

    $OrderID = mysqli_insert_id($conn);
    echo "<br><br>Redirecting...";
    ?>

    <script>
        function redirecting() {
            window.location.href = "confirmSales.php";
        }
        setTimeout(redirecting, 5000);
    </script>
</body>

</html>
