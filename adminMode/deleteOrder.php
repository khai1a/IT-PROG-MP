<html>

<head>
    <title>Edit Sales</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    include 'dbConnect.php';
    $OrderID = $_GET['OrderID'];
    $orderRow = $conn->query("SELECT * FROM ordertbl WHERE OrderID=" . $OrderID . ";");
    $orderR = mysqli_fetch_array($orderRow);

    echo "Are you sure you want to delete OrderID #" . $OrderID . "?";
    ?>
    <div class="delButtonLine pageButtons">
        <form method="POST" action="seniorDelete.php">
            <input type="hidden" name="OrderID" value="<?php echo $OrderID ?>">
            <button type="submit" class="confirmButton">Delete?</button>
        </form>
    </div>
</body>

</html>
