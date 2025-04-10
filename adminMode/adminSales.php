<html>

<head>
    <title>Sales Overview</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    //This menu is going to display all sales made. We shall start by calling all associated tables.
    include "dbconnect.php";
    $menuIDNames = $conn->query("SELECT MenuID, ItemName FROM menutbl");
    $sales = $conn->query("SELECT * FROM salestbl");
    $orderIDs = $conn->query("SELECT OrderID from ordertbl");
    ?>
    <table class="table">
        <tr>
            <th class="th">Sales ID</th>
            <th class="th">Raw Price</th>
            <th class="th">Price After Discount</th>
            <th class="th">Sales Contents</th>
            <th class="th">Payment Method</th>
            <th class="th">Dining Method</th>
            <th class="th">Sales Date</th>
            <th class="th">Edit or Delete</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_array($sales)) {
            ?>
            <tr class="mainRow">
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
                <td>
                    <table class="widgetTable">
                        <!--Functionality for EDIT and DELETE go here-->
                        <tr>
                            <form action="editSales.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="SalesID" value="<?php echo $row['SalesID']; ?>">
                                <input type="hidden" name="TotalRaw" value="<?php echo $row['TotalRaw']; ?>">
                                <input type="hidden" name="TotalDiscounted" value="<?php echo $row['TotalDiscounted']; ?>">
                                <!--Here will be the functionality to transfer the information about the quantities of all items sold in each sale.-->
                                <?php /* Fetch the fractioned menu table as an array. */
                                $menuArr = mysqli_fetch_array($menuIDNames);
                                /* Select the entire table of orders that match the current Sales ID. */
                                $sqlSaleQ = "SELECT * FROM ordertbl WHERE SalesID =" . $row['SalesID'];
                                /* And run it through a query. */
                                $orderRes = $conn->query($sqlSaleQ);

                                $OrderIDArr = [];
                                $OrderNameArr = [];
                                $OrderPriceArr = [];
                                $OrderQTYArr = [];
                                $i = 0;
                                while ($orderMenu = mysqli_fetch_array($orderRes)) {
                                    $menuName = $conn->query("SELECT ItemName FROM menutbl WHERE MenuID =" . $orderMenu['MenuID']);
                                    $menuPrice = $conn->query("SELECT Price FROM menutbl WHERE MenuID =" . $orderMenu['MenuID']);

                                    $OrderIDArr[$i] = $orderMenu['OrderID'];
                                    $OrderNameArr[$i] = mysqli_fetch_array($menuName)['ItemName'];
                                    $OrderPriceArr[$i] = mysqli_fetch_array($menuPrice)['Price'];
                                    $OrderQTYArr[$i] = $orderMenu['qty'];

                                    ?>
                                    <input type="hidden" name="OrderIDArr[]" value="<?php echo $OrderIDArr[$i]; ?>" >
                                    <input type="hidden" name="OrderNameArr[]" value="<?php echo $OrderNameArr[$i]; ?>" >
                                    <input type="hidden" name="OrderPriceArr[]" value="<?php echo $OrderPriceArr[$i]; ?>" >
                                    <input type="hidden" name="OrderQTYArr[]" value="<?php echo $OrderQTYArr[$i]; ?>" >
                                    <?php 
                                }
                                ?>                                
                                <!--Here is where it ends, in case the me of the time has to rewrite.-->
                                <input type="hidden" name="PaymentMethod" value="<?php echo $row['PaymentMethod']; ?>">
                                <input type="hidden" name="DiningMethod" value="<?php echo $row['DiningMethod']; ?>">
                                <input type="hidden" name="SaleDate" value="<?php echo $row['SaleDate']; ?>">
                                <button type="submit" class="editButton">Edit</button>
                            </form>
                        </tr>
                        <br>
                        <tr>
                            <form action="deleteSales.php" method="POST">
                                <input type="hidden" name="SalesID" value="<?php echo $row['SalesID']?>">
                                <button class="deleteButton">Delete</button>
                            </form>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>

    <br>
    <!--This table deliberately does not have a CREATE button.-->
    <!--LINKS to future pages go here.-->
    <div class="pageButtons">
        <a href="adminMenuPage.php" class="toPagesButton">Go to Menu Items</a>
        <a href="adminComboPage.php" class="toPagesButton">Go to Combos</a>
        <a href="adminRatings.php" class="toPagesButton">Go to Ratings</a>
        <a href="adminNavigator.html" class="toPagesButton">Go Home</a>
    </div>

</body>

</html>
