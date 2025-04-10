<html>

<head>
    <title>Sale Edit Confirmation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    include 'dbConnect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $SalesID = $_POST['SalesID'];
        $TotalRaw = $_POST['TotalRaw'];
        $TotalDiscounted = $_POST['TotalDiscounted'];
        /*Here will be where the sold items and their quantities will all go.*/
        $OrderIDArr = $_POST['OrderIDArr'];
        $OrderMenuArr = $_POST['OrderMenuArr'];
        $OrderPriceArr = $_POST['OrderPriceArr'];
        $OrderQTYArr = $_POST['OrderQTYArr'];

        $PaymentMethod = $_POST['PaymentMethod'];
        $DiningMethod = $_POST['DiningMethod'];
        $SaleDate = $_POST['SaleDate'];
    }
    $edited = 0;

    $salesRow = $conn->query("SELECT * FROM salestbl WHERE SalesID = '" . $SalesID . "';");
    $orderTable = $conn->query("SELECT * FROM ordertbl WHERE SalesID = '" . $SalesID . "';");
    $menuTable = $conn->query("SELECT MenuID, ItemName FROM menutbl;");

    while ($row = mysqli_fetch_array($salesRow)) {
        ?>
        <div class="confirmText">Confirming Edit of Sales ID <mark class="editedText"><?php echo $SalesID; ?></mark></div>
        <?php
        if ($row['TotalRaw'] !== $TotalRaw) {
            ?>
            <!--Confirmation for editing the Stars.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['TotalRaw'] ?></mark> to <mark
                    class="editedText"><?php echo $TotalRaw; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE salestbl 
            SET TotalRaw='" . $TotalRaw . "' 
            WHERE SalesID = '" . $SalesID . "';");
            $edited = 1;
        }
        if ($row['TotalDiscounted'] !== $TotalDiscounted) {
            ?>
            <!--Confirmation for editing the Comment.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['TotalDiscounted'] ?></mark> to <mark
                    class="editedText"><?php echo $TotalDiscounted; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE salestbl 
            SET TotalDiscounted='" . $TotalDiscounted . "' 
            WHERE SalesID = '" . $SalesID . "';");
            $edited = 1;
        }

        $i = 0;
        while ($subTable = mysqli_fetch_array($orderTable)) {
            if (
                $OrderMenuArr[$i] !== $subTable['MenuID'] ||
                $OrderQTYArr[$i] !== $subTable['qty']
            ) {
                mysqli_query($conn, "UPDATE ordertbl 
                SET MenuID=" . $OrderMenuArr[$i] . ", qty=" . $OrderQTYArr[$i] . " 
                WHERE OrderID=" . $OrderIDArr[$i] . ";");
                ?>
                <div class="confirmText">
                    Edited Order (MenuID, QTY): [<mark
                        class="editedText"><?php echo $OrderMenuArr[$i].", ".$OrderQTYArr[$i]; ?></mark>]
                </div>
                <?php

                $edited = 1;
            }
            $i++;
        }

        if ($row['PaymentMethod'] !== $PaymentMethod) {
            ?>
            <!--Confirmation for editing the Comment.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['PaymentMethod'] ?></mark> to <mark
                    class="editedText"><?php echo $PaymentMethod; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE salestbl 
            SET PaymentMethod='" . $PaymentMethod . "' 
            WHERE SalesID = '" . $SalesID . "';");
            $edited = 1;
        }
        if ($row['DiningMethod'] !== $DiningMethod) {
            ?>
            <!--Confirmation for editing the Comment.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['DiningMethod'] ?></mark> to <mark
                    class="editedText"><?php echo $DiningMethod; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE salestbl 
            SET DiningMethod='" . $DiningMethod . "' 
            WHERE SalesID = '" . $SalesID . "';");
            $edited = 1;
        }
        if ($row['SaleDate'] !== $SaleDate) {
            ?>
            <!--Confirmation for editing the Comment.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['SaleDate'] ?></mark> to <mark
                    class="editedText"><?php echo $SaleDate; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE salestbl 
            SET SaleDate='" . $SaleDate . "' 
            WHERE SalesID = '" . $SalesID . "';");
            $edited = 1;
        }

        if ($edited == 0) {
            ?>
            <div class="confirmText">No edit was detected. No changes were made.</div>
            <?php
        }
    }
    ?>
    <div class="pageButtons">
        <a href="adminSales.php" class="toPagesButton">Back</a>
    </div>
</body>

</html>
