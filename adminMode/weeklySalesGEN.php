<html>

<head>
    <title>Weekly Sales Generation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    include 'dbConnect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $DateFrom = $_POST['DateFrom'];
        $DateTo = $_POST['DateTo'];
        $sales = $conn->query("SELECT * FROM salestbl WHERE SaleDate BETWEEN '" . $DateFrom . "' AND '" . $DateTo . "';");
    } else {
        $sales = $conn->query("SELECT * FROM salestbl;");
    }


    $comboThings = $conn->query("SELECT ComboID, ComboName FROM combotbl");
    $comboItemThings = $conn->query("SELECT * FROM comboitemtbl");

    ?>
    <table>
        <tr>
            <th class="th">ID</th>
            <th class="th">Raw Price</th>
            <th class="th">Discounted Price</th>
            <th class="th">Combo Name</th>
            <th class="th">Date</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_array($sales)) {
            ?>
            <tr class="mainRow">
                <td><?php echo $row['SalesID'] ?></td>
                <td><?php echo $row['TotalRaw'] ?></td>
                <td>
                    <?php if ($row['TotalDiscounted'] !== $row['TotalRaw']) { ?>
                        <mark class="editedText">
                            <?php echo $row['TotalDiscounted'] ?>
                        </mark>
                    <?php } else {
                        echo $row['TotalDiscounted'];
                    } ?>
                </td>
                <td><?php
                $q = "
                SELECT c.ComboID, c.ComboName
                FROM combotbl c
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM comboitemtbl ci
                    WHERE ci.ComboID = c.ComboID
                    AND NOT EXISTS (
                        SELECT 1
                        FROM ordertbl o
                        WHERE o.SalesID = " . $row['SalesID'] . "
                        AND o.MenuID = ci.MenuID
                    )
                )
            ";
                $res = $conn->query($q);
                while ($subRow = mysqli_fetch_assoc($res)) {
                    echo $subRow['ComboName'];
                }
                ?></td>

                <td><?php echo $row['SaleDate'] ?></td>
            </tr>

            <?php

        }
        ?>
    </table>
    <br><br>
    <div class="pageButtons">
        <a href="adminNavigator.html" class="toPagesButton">Back</a>
    </div>
</body>

</html>
