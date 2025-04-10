<html>

<head>
    <title>Item Sales List</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    include 'dbConnect.php';
    $menu = $conn->query("SELECT * FROM menutbl;");
    $oq = "SELECT * FROM ordertbl WHERE MenuID = ";
    ?>
    <table class="table">
        <tr>
            <th class="th">ID</th>
            <th class="th">Item Name</th>
            <th class="th">Times Sold</th>
            <th class="th">Price/Unit</th>
            <th class="th">Price Total</th>
        </tr>
        
            <?php while ($row = mysqli_fetch_array($menu)) {
                $quant = 0; //Set quantity here, to be added upon itself later.
                ?>
                <tr class="mainRow">
                <td><?php echo $row['MenuID'] ?></td>
                <td><?php echo $row['ItemName'] ?></td>
                <?php $orders = $conn->query($oq . $row['MenuID'] . ";");
                while (isset($orders) && $subRow = mysqli_fetch_array($orders)) {
                    $quant += $subRow['qty'];
                }
                ?>
                <td><?php echo $quant ?></td>
                <td><?php echo $row['Price'] ?></td>
                <td><?php echo $row['Price'] * $quant ?></td>
            </tr>
        <?php } ?>
    </table>
    <br>

    <div class="pageButtons">
        <a href="adminNavigator.html" class="toPagesButton">Back</a>
    </div>
</body>

</html>
