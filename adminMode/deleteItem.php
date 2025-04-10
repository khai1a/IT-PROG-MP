<html>

<head>
    <title>Item Deletion</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $MenuID = $_POST['MenuID'];
        $ItemName = $_POST['ItemName'];
    }

    include "dbconnect.php";
    $menuRow = $conn->query("SELECT * FROM menutbl WHERE MenuID=" . $MenuID);
    /* If the MenuID exists in comboitemtbl. */
    $entry_exists = 0;
    $qu = "SELECT EXISTS (
                    SELECT 1
                    FROM comboitemtbl
                    WHERE MenuID=" . $MenuID . "
                ) AS entry_exists;";
    $res = mysqli_query($conn, $qu);
    if ($res) {
        $comboItems = $conn->query("SELECT * FROM comboitemtbl WHERE MenuID=" . $MenuID);
        $combos = $conn->query("SELECT * FROM combotbl");
        $row = mysqli_fetch_assoc($res);
        $entry_exists = $row['entry_exists'];
    }
    if ($entry_exists) {
        ?>
        <div class="confirmText">
            ERROR: MenuID <mark class="editedText"><?php echo $MenuID . " (" . $ItemName . ")" ?></mark> also exists in the
            following
            combos: <br>
            <?php
            while ($roww = mysqli_fetch_array($comboItems)) {
                if ($roww['MenuID'] == $MenuID) {
                    echo "ComboID: " . $roww['ComboID'] . "<br>";
                }
            }
            ?>
        </div>
        <?php
    } else {
        ?>
        <div class="confirmText">
            Are you sure you want to delete this Menu entry?
            <table class="table">
                <tr class="mainRow">
                    <?php
                    while ($row = mysqli_fetch_array($menuRow)) { ?>
                        <td> <?php echo $row['MenuID']; ?> </td>
                        <td> <img class="icon" src="<?php echo "../" . $row['ItemPic']; ?>" /> </td>
                        <td> <?php echo $row['ItemName']; ?> </td>
                        <td> <?php echo $row['Price']; ?> </td>
                        <?php

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
                            }
                    } ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php } ?>
    <div class="delButtonLine pageButtons">
        <form method="POST" action="seniorDelete.php">
            <input type="hidden" name="MenuID" value="<?php echo $MenuID ?>">
            <?php if (!$entry_exists) { ?>
                <button type="submit" class="confirmButton">Delete</button> <?php } else { ?>
                <a href="adminComboPage.php" class="toPagesButton">Go There?</a>
            <?php } ?>
            <a href="adminMenuPage.php" class="toPagesButton">Cancel</a>
        </form>
    </div>
</body>

</html>
