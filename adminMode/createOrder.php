<html>

<head>
    <title>Edit Sales</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    include 'dbConnect.php';
    $menu = $conn->query("SELECT MenuID, ItemName FROM menutbl");
    ?>
    <form action="confirmOrder.php" method="POST">
        <label>ID:</label>
        <input disabled="disabled" type="text" name="OrderID" readonly><br><br>

        <label>Sales ID:</label>
        <input disabled="disabled" type="text" name="SalesID" value="<?php echo $_GET['SalesID']; ?>" readonly><br><br>
        <input type="hidden" name="SalesID" value="<?php echo $_GET['SalesID']; ?>">

        <label>Menu Item:</label>
        <?php $menuThings = $conn->query("SELECT MenuID, ItemName FROM menutbl"); ?>
        <select name="ItemName">
            <?php
            $j = 0;
            while ($row = mysqli_fetch_array($menu)) {
                ?>
                <option value="<?php echo $row['ItemName']; ?>">
                    <?php echo $row['ItemName']; ?>
                </option>
                <?php
                $j++;
            } ?>
        </select>
        <br><br>


        <label>Quantity</label>
        <input type="number" name="Qty">

        <div class="pageButtons">
            <button type="submit" class="confirmButton">Done?</button>
        </div>
    </form>
</body>

</html>
