<html>

<head>
    <title>Menu Overview</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <!--This menu opens on overview displaying table data on a tab toggle.-->
    <?php
    //This section calls the menu, the combos, and what consists of those combos, respectively.
    include "dbconnect.php";
    $menu = $conn->query("SELECT * FROM menutbl");
    ?>
    <!-- MEAL TABLE AHEAD -->
    <table class="table">
        <tr>
            <th class="th">ID</th>
            <th class="th">Picture</th> <!--I wanted to reverse the order of this and the ItemName found on the SQL.-->
            <th class="th">Item Name</th>
            <th class="th">Price</th>
            <th class="th">Description</th>
            <th class="th">Allergen Picture</th> <!--here too-->
            <th class="th">Allergen</th>
            <th class="th">Spicy</th>
            <!--assuming 1 is spicy, 0 is not spicy (the description will be the output for readability).-->
            <th class="th">Active?</th>
            <!--assuming 1 is active (the description will be the output for readability).-->
            <th class="th">Item Type</th>
            <!--0 = drink, 1 = main, 2 = sides. This is the display for readability's sake.-->
            <th class="th">Edit or Delete</th>
        </tr>
        <?php
        /* Call the menu table and display accordingly. */
        while ($row = mysqli_fetch_array($menu)) {
            /* The goal is to shove the row contents into each table box by defining the values in the loop here. */
            ?>

            <tr class="mainRow">
                <td> <?php echo $row['MenuID']; ?> </td>
                <td> <img class="icon" src="<?php echo "../" . $row['ItemPic']; ?>" /> </td>
                <td> <?php echo $row['ItemName']; ?> </td>
                <td> <?php echo $row['Price']; ?> </td>
                <td> <?php echo $row['ItemDescription']; ?> </td>
                <?php
                if ($row['AllergenPic'] != NULL) {
                    ?>
                    <td> <img class="smallcon" src="<?php echo "../" . $row['AllergenPic']; ?>" /> </td>
                    <?php
                } else { ?>
                    <td></td>
                    <?php
                }
                ?>
                <td> <?php echo $row['Allergen']; ?> </td>
                <td>
                    <?php switch ($row['Spicy'] == 1) {
                        case 1:
                            echo "Yes";
                            break;
                        case 0:
                            echo "No";
                            break;
                        default:
                            echo "NULL";
                            break;
                    } ?>
                </td>
                <td>
                    <?php switch ($row['IsActive']) {
                        case 1:
                            echo "Yes";
                            break;
                        case 0:
                            echo "No";
                            break;
                        default:
                            echo "NULL";
                            break;
                    } ?>
                </td>
                <td>
                    <?php switch ($row['IsMeal']) {
                        case 2:
                            echo "Sides";
                            break;
                        case 1:
                            echo "Main Course";
                            break;
                        case 0:
                            echo "Drink";
                            break;
                        default:
                            echo "NULL";
                            break;
                    } ?>
                </td>
                <td>
                    <!--Functionality for EDIT and DELETE go here-->
                    <!--IDs are Edit: E[ID number] and D[ID number] respectively.-->
                    <table class="widgetTable">
                        <tr>
                            <form action="createEditItem.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="MenuID" value="<?php echo $row['MenuID']; ?>">
                                <input type="hidden" name="ItemPic" value="<?php echo $row['ItemPic']; ?>">
                                <input type="hidden" name="ItemName" value="<?php echo $row['ItemName']; ?>">
                                <input type="hidden" name="Price" value="<?php echo $row['Price']; ?>">
                                <input type="hidden" name="ItemDescription" value="<?php echo $row['ItemDescription']; ?>">
                                <input type="hidden" name="AllergenPic" value="<?php echo $row['AllergenPic']; ?>">
                                <input type="hidden" name="Allergen" value="<?php echo $row['Allergen']; ?>">
                                <input type="hidden" name="Spicy" value="<?php echo $row['Spicy']; ?>">
                                <input type="hidden" name="IsActive" value="<?php echo $row['IsActive']; ?>">
                                <input type="hidden" name="IsMeal" value="<?php echo $row['IsMeal']; ?>">
                                <button type="submit" class="editButton"
                                    id="<?php echo "E" . $row['MenuID']; ?>">Edit</button>
                            </form>
                        </tr>
                        <br>
                        <tr>
                            <form action="deleteItem.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="MenuID" value="<?php echo $row['MenuID']; ?>">
                                <input type="hidden" name="ItemName" value="<?php echo $row['ItemName']; ?>">   
                                <button type="submit" class="deleteButton"
                                    id="<?php echo "D" . $row['MenuID']; ?>">Delete</button>
                            </form>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <!--Functionality for CREATE goes here-->
            <!--REMEMBER: Do not allow creation past 10 items-->
            <td> </td>
            <td><a href="createEditItem.php">
                    <button class="createButton">
                        Create New Entry
                    </button>
                </a></td>
        </tr>
    </table>
    <br>
    <!--LINKS to future pages go here.-->
    <div class="pageButtons">
        <a href="adminComboPage.php" class="toPagesButton">Go to Combos</a>
        <a href="adminRatings.php" class="toPagesButton">Go to Ratings</a>
        <a href="adminSales.php" class="toPagesButton">Go to Sales</a>
        <a href="adminNavigator.html" class="toPagesButton">Go Home</a>
    </div>

</body>

</html>
