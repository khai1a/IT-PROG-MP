<html>

<head>
    <title>Create/Edit Menu Item</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <form action="confirmItem.php" method="POST" enctype="multipart/form-data">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $MenuID = $_POST['MenuID'];
            # IMPORTANT: This returns the string entry of the image in the database, NOT the actual image! 
            $ItemPic = $_POST['ItemPic'];
            $ItemName = $_POST['ItemName'];
            $Price = $_POST['Price'];
            $ItemDescription = $_POST['ItemDescription'];
            # IMPORTANT: This returns the string entry of the image in the database, NOT the actual image! 
            $AllergenPic = $_POST['AllergenPic'];
            $Allergen = $_POST['Allergen'];
            /*These last 3 all return values.*/
            $Spicy = $_POST['Spicy'];
            $IsActive = $_POST['IsActive'];
            $IsMeal = $_POST['IsMeal'];

            $editTag = "yes";
        }
        if (!isset($editTag)) {
            $editTag = "no";
        }
        ?>

        <label>ID:</label>
        <input disabled="disabled" type="text" name="MenuID" <?php if (isset($MenuID)) { ?> value="<?php echo $MenuID;
        } ?>" readonly><br><br>
        <input type="hidden" name="MenuID" <?php if (isset($MenuID)) { ?> value="<?php echo $MenuID;
        } ?>">

        <label>Picture: (Shown = Currently Uploaded Image, if any)</label><br>
        <?php if (isset($ItemPic)) { ?>
            <img class="icon" src="<?php echo "../" . $ItemPic; ?>" />
        <?php } ?>
        <input type="file" name="ItemPic" id="ItemPic" accept="image/*"><br><br>
        <label>Clear Image? </label>
        <input type="radio" name="ClearItemPic" value="1">Yes 
        <input type="radio" name="ClearItemPic" value="0" checked>No<br><br>

        <label>Item Name:</label>
        <input type="text" name="ItemName" <?php if (isset($ItemName)) { ?> value="<?php echo $ItemName;
        } ?>" maxlength="50" required><br><br>

        <label>Price:</label>
        <input type="number" name="Price" <?php if (isset($Price)) { ?> value="<?php echo $Price;
        } ?>"><br><br>

        <label>Description:</label>
        <textarea cols="50" rows="4" type="text" name="ItemDescription" maxlength="50"><?php if (isset($ItemDescription)) {
            echo $ItemDescription;
        } ?></textarea><br><br>

        <label>Allergen Picture:</label>
        <?php if (isset($AllergenPic)) { ?>
            <img class="icon" src="<?php echo "../" . $AllergenPic; ?>" />
        <?php } ?>
        <input type="file" name="AllergenPic" id="AllergenPic" accept="image/*"><br><br>
        <label>Clear Image? </label>
        <input type="radio" name="ClearAllPic" value="1">Yes 
        <input type="radio" name="ClearAllPic" value="0" checked>No<br><br>

        <label>Allergen:</label>
        <input type="text" name="Allergen" <?php if (isset($Allergen)) { ?> value="<?php echo $Allergen;
        } ?>" maxlength="50"><br><br>

        <label>Spicy Value: (0 = Not Spicy; 1 = Spicy)</label><br>
        <input type="radio" name="Spicy" <?php if (isset($Spicy) && $Spicy == 0) { ?> checked <?php }
        ; ?>value="0" required>0<br>
        <input type="radio" name="Spicy" <?php if (isset($Spicy) && $Spicy == 1) { ?> checked <?php }
        ; ?> value="1" required>1<br>
        <br>

        <label>Active Value: (0 = Not Active; 1 = Active)</label><br>
        <input type="radio" name="IsActive" <?php if (isset($IsActive) && $IsActive == 0) { ?> checked <?php }
        ; ?>value="0" required>0<br>
        <input type="radio" name="IsActive" <?php if (isset($IsActive) && $IsActive == 1) { ?> checked <?php }
        ; ?>value="1" required>1<br>
        <br>

        <label>Meal Value: (0 = Drink; 1 = Main Dish; 2 = Side Dish)</label><br>
        <input type="radio" name="IsMeal" <?php if (isset($IsMeal) && $IsMeal == 0) { ?> checked <?php }
        ; ?>value="0" required>0<br>
        <input type="radio" name="IsMeal" <?php if (isset($IsMeal) && $IsMeal == 1) { ?> checked <?php }
        ; ?>value="1" required>1<br>
        <input type="radio" name="IsMeal" <?php if (isset($IsMeal) && $IsMeal == 2) { ?> checked <?php }
        ; ?>value="2"required>2<br>


        <input type="hidden" name="editTag" value="<?php echo $editTag; ?>">
        <div class="pageButtons">
            <button type="submit" class="confirmButton">Done?</button>
        </div>
    </form>
    <br>

    <div class="pageButtons">
        <a href="adminMenuPage.php" class="toPagesButton">Back</a>
    </div>
</body>

</html>
