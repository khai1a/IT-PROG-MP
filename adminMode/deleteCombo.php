<html>

<head>
    <title>Combo Deletion</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ComboID = $_POST['ComboID'];
    }

    include "dbconnect.php";
    $comboRow = $conn->query("SELECT * FROM combotbl WHERE ComboID=" . $ComboID);
    $comboItems = $conn->query("SELECT * FROM comboitemtbl WHERE ComboID=" . $ComboID);

    while ($row = mysqli_fetch_array($comboRow)) {
        ?>
        <div class="confirmText">
            Are you sure you want to delete this Combo entry?
            <table class="table">
                <tr class="mainRow"></tr>
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
                /* countOfLoop is a counter used for the form that will POST the data forward. */
                $countofLoop = 1;
                while ($rowOfComps = mysqli_fetch_array($combo_components)) {
                    /* The next step is to link the current menu IDs to the existing menu table. */
                    /* This is done first by pulling the menu table item that matches the current menu ID. */
                    if (isset($rowOfComps['MenuID'])) {
                        $sqlMenuQ = "SELECT ItemName FROM menutbl WHERE MenuID =" . $rowOfComps['MenuID'];
                        $menuName = $conn->query($sqlMenuQ);
                        /* And then we fetch the single-item array (as the only fetched column is ItemName, and the only row fetched is the one with the matching MenuID.)*/
                        $menuFetch = mysqli_fetch_array($menuName);
                        ?>
                        <!--The cell ends up in the loop as a result, but as this is a nested loop or the 3 component columns, this is no problem.-->
                        <td> <?php
                        /* This portion generates variable names Component1, Component2, and Component3. */
                        $nameOfComponent = "Component" . $countofLoop;
                        $$nameOfComponent = $menuFetch['ItemName'];
                        echo "{$$nameOfComponent}"; ?>
                        </td>
                        <?php
                    }
                    $countofLoop++;
                }
    }
    ?>
        </table>
    </div>
    <div class="delButtonLine pageButtons">
        <form method="POST" action="seniorDelete.php">
            <input type="hidden" name="ComboID" value="<?php echo $ComboID ?>">
            <button type="submit" class="confirmButton">Delete</button>
            <a href="adminComboPage.php" class="toPagesButton">Cancel</a>
        </form>
    </div>
</body>

</html>
