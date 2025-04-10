<html>

<head>
    <title>Combo Overview</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    /* This section calls the menu, the combos, and what consists of those combos, respectively. */
    include "dbconnect.php";
    $menu = $conn->query("SELECT * FROM menutbl");
    $combos = $conn->query("SELECT * FROM combotbl");
    ?>
    <!-- COMBO TABLE AHEAD -->
    <table class="table">
        <tr>
            <th class="th">ID</th>
            <th class="th">Combo Name</th>
            <th class="th">Discount</th>
            <th class="th">Order Frequency</th>
            <th class="th">Component 1</th>
            <!--These 3 Component columns are printed by name, ported from [comboitemtbl], for ease of readability.-->
            <th class="th">Component 2</th>
            <th class="th">Component 3</th>
            <th class="th">Edit or Delete</th>
        </tr>
        <?php
        /* Call the menu table and display each component of each combo accordingly. */
        /* The "td" tag for the Component columns lies within this loop. */
        while ($row = mysqli_fetch_array($combos)) {
            /* The goal is to shove the row contents into each table box by defining the values in the loop here. */
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
                ?>

                <!--I want the JS functionality on these buttons as much as possible.-->
                <td>
                    <table class="widgetTable">
                        <!--Functionality for EDIT and DELETE go here-->
                        <!--IDs are Edit: E[row number] and D[row number] respectively.-->
                        <form action="createEditCombo.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="ComboID" value="<?php echo $row['ComboID']; ?>">
                            <input type="hidden" name="ComboName" value="<?php echo $row['ComboName']; ?>">
                            <input type="hidden" name="Discount" value="<?php echo $row['Discount']; ?>">
                            <input type="hidden" name="TimesUsed" value="<?php echo $row['TimesUsed']; ?>">
                            <!--The next portion is a way to transfer the component data from comboitemtbl through this form.-->
                            <input type="hidden" name="Component1" value="<?php echo $Component1; ?>">
                            <input type="hidden" name="Component2" value="<?php echo $Component2; ?>">
                            <input type="hidden" name="Component3" value="<?php echo $Component3; ?>">
                            <button type="submit" class="editButton" id="<?php echo "E" . $row['ComboID']; ?>">Edit</button>
                        </form>
                        <br>
                        <form action="deleteCombo.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="ComboID" value="<?php echo $row['ComboID']; ?>">
                            <input type="hidden" name="ComboName" value="<?php echo $row['ComboName']; ?>">
                            <input type="hidden" name="Discount" value="<?php echo $row['Discount']; ?>">
                            <input type="hidden" name="TimesUsed" value="<?php echo $row['TimesUsed']; ?>">
                            <!--The next portion is a way to transfer the component data from comboitemtbl through this form.-->
                            <input type="hidden" name="Component1" value="<?php echo $Component1; ?>">
                            <input type="hidden" name="Component2" value="<?php echo $Component2; ?>">
                            <input type="hidden" name="Component3" value="<?php echo $Component3; ?>">
                            <button type="submit" class="deleteButton"
                                id="<?php echo "D" . $row['ComboID']; ?>">Delete</button>
                        </form>
                    </table>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <!--Functionality for CREATE goes here-->
            <td> </td>
            <td><a href="createEditCombo.php">
                    <button class="createButton">
                        Create New Entry
                    </button>
                </a></td>
            <?php
            ?>
        </tr>
    </table>
    <br>
    <!--LINKS to future pages go here.-->
    <div class="pageButtons">
        <a href=" adminMenuPage.php" class="toPagesButton">Go to Menu Items</a>
        <a href="adminRatings.php" class="toPagesButton">Go to Ratings</a>
        <a href="adminSales.php" class="toPagesButton">Go to Sales</a>
        <a href="adminNavigator.html" class="toPagesButton">Go Home</a>
    </div>
</body>

</html>
