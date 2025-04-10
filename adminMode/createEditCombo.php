<html>

<head>
    <title>Create/Edit Combo</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <form action="confirmCombo.php" method="POST" enctype="multipart/form-data">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ComboID = $_POST['ComboID'];
            $ComboName = $_POST['ComboName'];
            $Discount = $_POST['Discount'];
            $TimesUsed = $_POST['TimesUsed'];
            $Component1 = $_POST['Component1'];
            $Component2 = $_POST['Component2'];
            $Component3 = $_POST['Component3'];

            $editTag = "yes";
        }
        if (!isset($editTag)) {
            $editTag = "no";
        }

        include "dbconnect.php";
        ?>

        <label>Combo ID</label>
        <input disabled="disabled" type="text" <?php if (isset($ComboID)) { ?> value="<?php echo $ComboID;
        } ?>" readonly><br><br>
        <input type="hidden" name="ComboID" <?php if (isset($ComboID)) { ?> value="<?php echo $ComboID;
        } ?>">

        <label>Combo Name</label>
        <input type="text" name="ComboName" <?php if (isset($ComboName)) { ?> value="<?php echo $ComboName;
        } ?>" maxlength="50"><br><br>

        <label>Discount</label>
        <input type="number" name="Discount" <?php if (isset($Discount)) { ?> value="<?php echo $Discount;
        } ?>"><br><br>

        <label>Times Used</label>
        <input type="number" name="TimesUsed" <?php if (isset($TimesUsed)) { ?> value="<?php echo $TimesUsed;
        } else {echo 0;} ?>"><br><br>

        <!--The following is a set of PHP code to print out radio options for the following components:-->
        <!--Note that despite displaying the Menu Name until this point, these will all return the Menu ID instead.-->
        <label>Component 1 Selector</label> <br>
        <?php
        $menuIDNames = $conn->query("SELECT MenuID, ItemName, IsActive FROM menutbl");
        while ($row = mysqli_fetch_array($menuIDNames)) {
            if ($row['IsActive'] == 1) {
                ?>
                <input type="radio" name="Component1" <?php if (isset($Component1) && $Component1 == $row['ItemName']) { ?>
                        checked <?php }
                ; ?> value="<?php echo $row['MenuID']; ?>" required>
                <?php echo $row['ItemName']; ?><br>
                <?php
            }
        }
        ?><br>
        <label>Component 2 Selector</label> <br>
        <?php
        $menuIDNames = $conn->query("SELECT MenuID, ItemName, IsActive FROM menutbl");
        while ($row = mysqli_fetch_array($menuIDNames)) {
            if ($row['IsActive'] == 1) {
                ?>
                <input type="radio" name="Component2" <?php if (isset($Component2) && $Component2 == $row['ItemName']) { ?>
                        checked <?php }
                ; ?> value="<?php echo $row['MenuID']; ?>" required>
                <?php echo $row['ItemName']; ?><br>
                <?php
            }
        }
        ?><br>
        <label>Component 3 Selector</label> <br>
        <?php
        $menuIDNames = $conn->query("SELECT MenuID, ItemName, IsActive FROM menutbl");
        while ($row = mysqli_fetch_array($menuIDNames)) {
            if ($row['IsActive'] == 1) {
                ?>
                <input type="radio" name="Component3" <?php if (isset($Component3) && $Component3 == $row['ItemName']) { ?>
                        checked <?php }
                ; ?> value="<?php echo $row['MenuID']; ?>" required>
                <?php echo $row['ItemName']; ?><br>
                <?php
            }
        }
        ?><br>

        <input type="hidden" name="editTag" value="<?php echo $editTag; ?>">
        <div class="pageButtons">
            <button type="submit" class="confirmButton">Done?</button>
        </div>

    </form>
    <br>

    <div class="pageButtons">
        <a href="adminComboPage.php" class="toPagesButton">Back</a>
    </div>
</body>

</html>
