<html>

<head>
    <title>Edit Sales</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    $OrderIDArr = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SalesID = $_POST['SalesID'];
        $TotalRaw = $_POST['TotalRaw'];
        $TotalDiscounted = $_POST['TotalDiscounted'];
        /*Here will be where the sold items and their quantities will all go.*/
        $OrderIDArr = $_POST['OrderIDArr'];
        $OrderNameArr = $_POST['OrderNameArr'];
        $OrderPriceArr = $_POST['OrderPriceArr'];
        $OrderQTYArr = $_POST['OrderQTYArr'];
        $PaymentMethod = $_POST['PaymentMethod'];
        $DiningMethod = $_POST['DiningMethod'];
        $SaleDate = $_POST['SaleDate'];
    }


    include 'dbConnect.php';

    ?>
    <?php
    // echo "<pre>"; 
    // print_r($_POST);
    // echo "</pre>";
    ?>

    <form action="confirmSales.php" method="POST">
        <label>Sales ID</label>
        <input disabled="disabled" type="text" <?php if (isset($SalesID)) { ?> value="<?php echo $SalesID;
        } ?>" readonly><br><br>
        <input type="hidden" name="SalesID" <?php if (isset($SalesID)) { ?> value="<?php echo $SalesID;
        } ?>">

        <label>Raw Price</label>
        <input type="number" name="TotalRaw" <?php if (isset($TotalRaw)) { ?> value="<?php echo $TotalRaw;
        } ?>"><br><br>

        <label>Discounted Price</label>
        <input type="number" name="TotalDiscounted" <?php if (isset($TotalDiscounted)) { ?> value="<?php echo $TotalDiscounted;
        } ?>"><br><br>

        <!-- This is where the sold items and quantity information will go.-->
        <label>Receipt/Order Table</label>
        <table class="salesTBL">
            <tr>
                <th class="prodHead">Order ID</th>
                <th class="prodHead">Item Name</th>
                <th class="prodHead">Order Price</th>
                <th class="prodHead">Order QTY</th>
                <th class="prodHead">Delete?</th>
            </tr>

            <?php
            $i = 0;
            if (is_array($OrderIDArr)) {
                foreach ($OrderIDArr as $i => $OorderID) { ?>
                    <tr class="mainRow">
                        <td><input disabled="disabled" type="text" <?php if (isset($OrderIDArr[$i])) { ?> value="<?php echo $OorderID;
                        } ?>" readonly></td>
                        <input type="hidden" name="OrderIDArr[]" <?php if (isset($OrderIDArr[$i])) { ?> value="<?php echo $OorderID;
                        } ?>">
                        <td>
                            <?php $menuThings = $conn->query("SELECT MenuID, ItemName FROM menutbl"); ?>
                            <select name="OrderMenuArr[]">
                                <?php
                                $j = 0;
                                while ($row = mysqli_fetch_array($menuThings)) {
                                    ?>
                                    <option id="<?php echo $row['ItemName']; ?>" value="<?php echo $row['MenuID']?>" <?php if ($OrderNameArr[$i] == $row['ItemName']) { ?> selected <?php } ?>>
                                        <?php echo $row['ItemName']; ?>
                                    </option>
                                    <?php
                                    $j++;
                                } ?>
                            </select>
                        </td>
                        <td><input type="number" name="OrderPriceArr[]" <?php if (isset($OrderPriceArr[$i])) { ?> value="<?php echo $OrderPriceArr[$i];
                        } ?>"></td>
                        <td><input type="number" name="OrderQTYArr[]" <?php if (isset($OrderQTYArr[$i])) { ?> value="<?php echo $OrderQTYArr[$i];
                        } ?>"></td>
                        <td>
                            <a href="deleteOrder.php?OrderID=<?php echo $OorderID;?>">
                                <button type="button" class="deleteButton">Delete Entry</button>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
            }
            ?>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href="createOrder.php?SalesID=<?php echo $SalesID; ?>">
                        <button type="button" class="createButton">
                            Create New Entry
                        </button>
                    </a>
                </td>
            </tr>
        </table>

        <label>Payment Method</label>
        <input type="text" name="PaymentMethod" <?php if (isset($PaymentMethod)) { ?> value="<?php echo $PaymentMethod;
        } ?>" maxlength="50"><br><br>

        <label>Dining Method</label>
        <input type="text" name="DiningMethod" <?php if (isset($DiningMethod)) { ?> value="<?php echo $DiningMethod;
        } ?>" maxlength="50"><br><br>

        <label>Sale Date</label>
        <input type="date" name="SaleDate" <?php if (isset($SaleDate)) { ?> value="<?php echo $SaleDate;
        } ?>"><br><br>

        <div class="pageButtons">
            <button type="submit" class="confirmButton">Done?</button>
        </div>

    </form>
    <br>

    <div class="pageButtons">
        <a href="adminSales.php" class="toPagesButton">Back</a>
    </div>

</body>

</html>
