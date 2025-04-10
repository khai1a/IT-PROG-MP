<html>

<head>
    <title>Item Confirmation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $MenuID = $_POST['MenuID'];
        /* This uploads an actual picture, rather than just the filename like in the database. */
        if (isset($_FILES['ItemPic'])) {
            /* This sequence turns the file upload human-readable. */
            $ItemPicName = $_FILES['ItemPic']['name'];
            $ItemPicTMP = $_FILES['ItemPic']['tmp_name'];
        }
        $ClearItemPic = $_POST['ClearItemPic'];

        $ItemName = $_POST['ItemName'];
        $Price = $_POST['Price'];
        $ItemDescription = $_POST['ItemDescription'];

        /* Same here. */
        if (isset($_FILES['AllergenPic'])) {
            $AllPicName = $_FILES['AllergenPic']['name'];
            $AllPicTMP = $_FILES['AllergenPic']['tmp_name'];
        }
        $ClearAllPic = $_POST['ClearAllPic'];

        $Allergen = $_POST['Allergen'];
        $Spicy = $_POST['Spicy'];
        $IsActive = $_POST['IsActive'];
        $IsMeal = $_POST['IsMeal'];

        $dirName = "images/";

        $editTag = $_POST['editTag'];
    }

    include "dbconnect.php";

    /* Simple function to concatenate a filename and its folder together, in the same format as in the database [ex. images/example.jpg]. */
    /* NOTE: As adminMode is a subfolder, this will need to be appended with "../" to access the image properly. */
    function catFilename($filename)
    {
        global $dirName;
        return $dirName . "" . $filename;
    }

    /* Another simple function that builds the image checking system into itself. */
    function imageChecker($temp, $uploadToCol, $cattedFileName)
    {
        global $conn;
        global $MenuID;
        $booly = false;

        if (empty($temp) || !file_exists($temp)) {
            echo "The file does not exist.";
            return $booly;
        }

        if (getimagesize($temp) === false) {
            echo "The file is not an image.";
            return $booly;
        }

        $maxFileSize = 2 * 1024 * 1024; /* 2MB */
        if (filesize($temp) > $maxFileSize) {
            echo "The file exceeds 2MB. Look for a smaller file.";
            return $booly;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileInfo = getimagesize($temp);

        if (!in_array($fileInfo['mime'], $allowedTypes)) {
            echo "Invalid image type (Looking for JPEG, PNG, GIF, or JPG).";
            return $booly;
        }

        if (move_uploaded_file($temp, "../" . $cattedFileName)) {
            mysqli_query($conn, "UPDATE menutbl SET " . $uploadToCol . " = '" . $cattedFileName . "' WHERE MenuID = " . $MenuID . ";");
            $booly = true;
        }

        return $booly;
    }
    /* Counts the meals to see if they go above capacity with this next edit. */
    function mealCounter($MenuID, $IsActive, $IsMeal, $editTag)
    {
        $booly = 0;

        include "dbconnect.php";
        $countMe = $conn->query("SELECT IsActive, IsMeal FROM menutbl");
        $capacity = 0;
        while ($row = mysqli_fetch_array($countMe)) {
            if ($row['IsActive'] == 1) {
                switch ($row['IsMeal']) {
                    case 0:
                        $capacity += 0.5;
                        break;
                    case 1:
                        $capacity += 1;
                        break;
                    case 2:
                        $capacity += 1;
                        break;
                }
            }
        }
        if ($editTag == "yes" && $capacity >= 9.5) { /* Is this an edited entry? + Is the capacity nearing full (10)? */
            $rowToEdit = mysqli_fetch_array($conn->query("SELECT MenuID, IsActive, IsMeal FROM menutbl WHERE MenuID ='" . $MenuID . "';"));
            if ($IsActive > $rowToEdit['IsActive'] && $IsMeal > 0) { /* Is one of the edits making this entry active? + Drink version */
                if ($capacity + 1 > 10) {
                    $booly = 1;
                }
            } elseif ($IsActive > $rowToEdit['IsActive'] && $IsMeal == 0) { /* Is one of the edits making this entry active? + Full Meal version */
                if ($capacity + 0.5 > 10) {
                    $booly = 1;
                }
            }
        } else { /* We are not editing, we are adding. */
            if ($IsActive == 1) {
                if ($IsMeal > 0) { /* It isn't a drink. */
                    if ($capacity + 1 > 10) {
                        $booly = 1;
                    }
                } else { /* It is a drink. */
                    if ($capacity + 0.5 > 10) {
                        $booly = 1;
                    }
                }
            }
        }

        return $booly;
    }
    /* If/else statement for if the following goes through at all. */
    if (mealCounter($MenuID, $IsActive, $IsMeal, $editTag) == 1) {
        ?>
        <div class="confirmText">
            There is no more space for this edit/creation.
        </div>
        <?php
    } else {
        if ($editTag == "no") {
            mysqli_query($conn, "INSERT INTO menutbl (ItemName) VALUES (NULL);");
            $MenuID = mysqli_insert_id($conn);
        }
        $menu = $conn->query("SELECT * FROM menutbl WHERE MenuID = '" . $MenuID . "';");

        $edited = 0;
        while ($row = mysqli_fetch_array($menu)) {
            ?>
            <div class="confirmText">Confirming Edit of Menu ID <mark class="editedText"><?php echo $MenuID; ?></mark></div>
            <?php
            /* As people won't upload images if they don't need to edit, a comparison value is not needed, merely the isset. */
            if (isset($_FILES['ItemPic']) && $_FILES['ItemPic']['error'] === UPLOAD_ERR_OK && !empty($_FILES['ItemPic']['tmp_name'])) {
                ?>
                <!--Confirmation for editing the Item Picture.-->
                <div class="confirmText">
                    <?php if (imageChecker($ItemPicTMP, "ItemPic", catFilename($ItemPicName))) { ?>
                        <br> Uploaded Menu Image: <br>
                        <img class="icon" src="../<?php echo catFilename($ItemPicName); ?>" /> <br>
                    </div>
                    <?php
                    $edited = 1;
                    } else {
                        ?>
                    <br>Image not uploaded. Check the file type, as well as the size. (less than 2MB only).<br>
                    <?php
                    }
            }
            if (isset($ClearItemPic) && $ClearItemPic == 1) {
                mysqli_query($conn, "UPDATE menutbl SET ItemPic = NULL WHERE MenuID = " . $MenuID . ";");
                echo '<div class="confirmText">';
                echo '<br>Item Picture has been cleared.<br>';
                echo '</div>';
                $edited = 1;
            }
            if (isset($ItemName) && $row['ItemName'] !== $ItemName) {
                ?>
                <!--Confirmation for editing the Item Name.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo $row['ItemName'] ?></mark> to <mark
                        class="editedText"><?php echo $ItemName; ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET ItemName='" . $ItemName . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }
            if (isset($Price) && $row['Price'] !== $Price) {
                ?>
                <!--Confirmation for editing the Price.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo $row['Price'] ?></mark> to <mark
                        class="editedText"><?php echo $Price; ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET Price='" . $Price . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }
            if (isset($ItemDescription) && $row['ItemDescription'] !== $ItemDescription) {
                ?>
                <!--Confirmation for editing the Item Description.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo $row['ItemDescription'] ?></mark> to <mark
                        class="editedText"><?php echo $ItemDescription; ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET ItemDescription='" . $ItemDescription . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }
            if (isset($Allergen) && $row['Allergen'] !== $Allergen) {
                ?>
                <!--Confirmation for editing the Allergen.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo $row['Allergen'] ?></mark> to <mark
                        class="editedText"><?php echo $Allergen; ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET Allergen='" . $Allergen . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }
            /* As people won't upload images if they don't need to edit, a comparison value is not needed, merely the isset. */
            if (isset($_FILES['AllergenPic']) && !empty($_FILES['AllergenPic']['tmp_name'])) {
                $AllPicName = $_FILES['AllergenPic']['name'];
                $AllPicTMP = $_FILES['AllergenPic']['tmp_name'];

                if (imageChecker($AllPicTMP, "AllergenPic", catFilename($AllPicName))) {
                    echo '<div class="confirmText">';
                    echo '<br> Uploaded Allergen Image: <br>';
                    echo '<img class="icon" src="../' . catFilename($AllPicName) . '" /> <br>';
                    echo '</div>';
                    $edited = 1;
                } else {
                    echo '<div class="confirmText">';
                    echo '<br>Image not uploaded. Check the file type, as well as the size. (less than 2MB only).<br>';
                    echo '</div>';
                }
            }

            if (isset($ClearAllPic)  && $ClearAllPic == 1) {
                mysqli_query($conn, "UPDATE menutbl SET AllergenPic = NULL WHERE MenuID = " . $MenuID . ";");
                echo '<div class="confirmText">';
                echo '<br>Allergen Picture has been cleared.<br>';
                echo '</div>';
                $edited = 1;
            }

            function spiceNamer($val)
            {
                $name = " ";
                if (isset($val)) {
                    switch ($val) {
                        case 0:
                            $name = "Not Spicy";
                            break;
                        case 1:
                            $name = "Spicy";
                            break;
                    }
                }
                return $name;
            }
            if (isset($Spicy) && $row['Spicy'] !== $Spicy) {

                ?>
                <!--Confirmation for editing the Spice Value.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo spiceNamer($row['Spicy']); ?></mark> to <mark
                        class="editedText"><?php echo spiceNamer($Spicy); ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET Spicy='" . $Spicy . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }

            function activeNamer($val)
            {
                $name = " ";
                if (isset($val)) {
                    switch ($val) {
                        case 0:
                            $name = "Inactive";
                            break;
                        case 1:
                            $name = "Active";
                            break;
                    }
                }
                return $name;
            }
            if (isset($IsActive) && $row['IsActive'] !== $IsActive) {
                ?>
                <!--Confirmation for editing the Active Value.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo activeNamer($row['IsActive']); ?></mark> to <mark
                        class="editedText"><?php echo activeNamer($IsActive);
                        ; ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET IsActive='" . $IsActive . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }

            function mealNamer($val)
            {
                $name = " ";
                if (isset($val)) {
                    switch ($val) {
                        case 0:
                            $name = "Drink";
                            break;
                        case 1:
                            $name = "Main Course";
                            break;
                        case 2:
                            $name = "Side";
                            break;
                    }
                }
                return $name;
            }
            if (isset($IsMeal) && $row['IsMeal'] !== $IsMeal) {
                ?>
                <!--Confirmation for editing the Meal Value.-->
                <div class="confirmText">
                    Edited: [<mark class="editedText"><?php echo mealNamer($row['IsMeal']); ?></mark> to <mark
                        class="editedText"><?php echo mealNamer($IsMeal); ?></mark>]
                </div>
                <?php
                mysqli_query($conn, "UPDATE menutbl 
            SET IsMeal='" . $IsMeal . "' 
            WHERE MenuID = '" . $MenuID . "';");
                $edited = 1;
            }

            if (
                $edited == 0
            ) {
                ?>
                <div class="confirmText">No edit was detected. No changes were made.</div>
                <?php
            }
        }
    } ?>
    <br>
    <div class="pageButtons">
        <a href="adminMenuPage.php" class="toPagesButton">Back</a>
    </div>


</body>

</html>
