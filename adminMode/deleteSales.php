<html>

<head>
    <title>Delete Sales</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SalesID = $_POST['SalesID'];
    }
    include 'dbConnect.php';
    $salesRow = $conn->query("SELECT * FROM salestbl WHERE SalesID=" . $SalesID . ";");
    $orderTBL = $conn->query("SELECT * FROM ordertbl WHERE SalesID=" . $SalesID . ";");
    $menuIDNames = $conn->query("SELECT MenuID, ItemName FROM menutbl");

    while ($row = mysqli_fetch_array($salesRow)) {
        ?>
        <div class="confirmText">
            Are you sure you want to delete this Sales entry?
            <table class="table">
                <tr class="mainRow"></tr>
                <td> <?php echo $row['SalesID']; ?> </td>
                <td> <?php echo $row['TotalRaw']; ?> </td>
                <td> <?php echo $row['TotalDiscounted']; ?> </td>
                <td>
                    <table class="toTheLeft">
                        <?php

                        /* Fetch the fractioned menu table as an array. */
                        $menuArr = mysqli_fetch_array($menuIDNames);
                        /* Select the entire table of orders that match the current Sales ID. */
                        $sqlSaleQ = "SELECT * FROM ordertbl WHERE SalesID =" . $row['SalesID'];
                        /* And run it through a query. */
                        $orderRes = $conn->query($sqlSaleQ);
                        /* Before displaying it in a nested table. */
                        while ($orderMenu = mysqli_fetch_array($orderRes)) {

                            /* Calling singular arrays for both the menu name and price. */
                            $menuName = $conn->query("SELECT ItemName FROM menutbl WHERE MenuID =" . $orderMenu['MenuID']);
                            $menuPrice = $conn->query("SELECT Price FROM menutbl WHERE MenuID =" . $orderMenu['MenuID']);
                            ?>
                            <tr>
                                <!--And through witchcraft (fetching the singular array) you called the names.-->
                                <td class="toTheLeft">
                                    <?php echo mysqli_fetch_array($menuName)['ItemName'] . " (Php" . mysqli_fetch_array($menuPrice)['Price'] . ")"; ?>
                                </td>
                                <td class="toTheRight"> <?php echo "x" . $orderMenu['qty']; ?> </td>
                            </tr>
                            <?php

                        }
                        ?>
                    </table>
                </td>
                <td> <?php echo $row['PaymentMethod']; ?> </td>
                <td> <?php echo $row['DiningMethod']; ?> </td>
                <td> <?php echo $row['SaleDate']; ?> </td>
            </table>
        </div>

        <div class="delButtonLine pageButtons">
            <form method="POST" action="seniorDelete.php">
                <input type="hidden" name="SalesID" value="<?php echo $SalesID ?>">
                <button type="submit" class="confirmButton">Delete</button>
                <a href="adminSales.php" class="toPagesButton">Cancel</a>
            </form>
        </div>
    <?php }
    ?>
</body>

</html>
