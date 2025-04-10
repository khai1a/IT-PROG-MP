<html>

<head>
    <title>Combo Sales List</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php

    include 'dbConnect.php';
    $combo = $conn->query("SELECT * FROM combotbl;");
    ?>
    <table class="table">
        <tr>
            <th class="th">Combo ID</th> <!-- 0 -->
            <th class="th">Name</th> <!-- 1 -->
            <th class="th">Discount</th> <!-- 2 -->
            <th class="th">Frequency</th> <!-- 3 -->
            <th class="th">Item 1</th> <!-- 4 -->
            <th class="th">Price</th> <!-- 5 -->
            <th class="th">Item 2</th> <!-- 6 -->
            <th class="th">Price</th> <!-- 7 -->
            <th class="th">Item 3</th> <!-- 8 -->
            <th class="th">Price</th> <!-- 9 -->
            <th class="th">Raw Price/Unit</th> <!-- 10 -->
            <th class="th">Discounted Price/Unit</th> <!-- 11 -->
            <th class="th">Discounted Price Total</th> <!-- 12 -->
            <th class="th">Discount Saved</th> <!-- 13 -->
        </tr>
        <?php
        while ($row = mysqli_fetch_array($combo)) {
            ?>
            <tr class="mainRow">
                <td> <?php echo $row['ComboID']; ?> </td>
                <td> <?php echo $row['ComboName']; ?> </td>
                <td> <?php echo $row['Discount']; ?> </td>
                <td> <?php echo $row['TimesUsed']; ?> </td>
                <?php
                /* This selects all rows in comboitemtbl that match the current Combo ID. */
                $sqlCompQ = "SELECT * FROM comboitemtbl WHERE ComboID =" . $row['ComboID'];
                /* Then it runs the query. */
                $combo_components = $conn->query($sqlCompQ);
                /* And fetches the array for use. */
                /* The "td" tag for the Component columns lies within this loop. */
                /* Due to needing basic calculation, the price value is initialized here: */
                $priceTotaler = 0;
                while ($rowOfComps = mysqli_fetch_array($combo_components)) {
                    /* The next step is to link the current menu IDs to the existing menu table. */
                    /* This is done first by pulling the menu table item that matches the current menu ID. */
                    if (isset($rowOfComps['MenuID'])) {
                        /* For combosSoldGEN, this now calls the menu ID, name, and price. */
                        $sqlMenuQ = "SELECT MenuID, ItemName, Price FROM menutbl WHERE MenuID =" . $rowOfComps['MenuID'];
                        $menuName = $conn->query($sqlMenuQ);
                        /* And then we fetch the single-item array (as the only fetched column is ItemName, and the only row fetched is the one with the matching MenuID.)*/
                        $menuFetch = mysqli_fetch_array($menuName);
                        /* Due to needing basic calculation, the price value is saved here: */

                        $priceShower = $menuFetch['Price'];
                        $priceTotaler += $priceShower;
                        ?>
                        <!--The cell ends up in the loop as a result, but as this is a nested loop or the 3 component columns, this is no problem.-->
                        <td> <?php echo $menuFetch['ItemName']; ?> </td>
                        <td> <?php echo $priceShower ?></td>
                        <?php
                    }
                }
                ?>
                <td><?php echo $priceTotaler; ?></td>
                <td>
                    <?php echo $priceTotaler - $row['Discount']; ?>
                </td>
                <td><?php echo ($priceTotaler - $row['Discount']) * $row['TimesUsed']; ?></td>
                <td><?php echo ($row['Discount'] * $row['TimesUsed']); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br>

    <div class="pageButtons">
        <a href="adminNavigator.html" class="toPagesButton">Back</a>
    </div>
</body>

</html>
