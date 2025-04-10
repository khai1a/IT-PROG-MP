<html>

<head>
    <title>Combo Confirmation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <!--This will come directly from the createEditCombo.php form.-->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ComboID = $_POST['ComboID'];
        $ComboName = $_POST['ComboName'];
        $Discount = $_POST['Discount'];
        $TimesUsed = $_POST['TimesUsed'];
        //i wish i found this out earlier lowk
        $Components = [
            'Component1' => $_POST['Component1'],
            'Component2' => $_POST['Component2'],
            'Component3' => $_POST['Component3'],
        ];

        $editTag = $_POST['editTag'];
    }

    include "dbconnect.php";

    if ($editTag == "no") {
        mysqli_query($conn, "INSERT INTO combotbl (ComboName) VALUES (NULL);");
        $ComboID = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO comboitemtbl (ComboID, MenuID) VALUES (" . $ComboID . ", NULL);");
        mysqli_query($conn, "INSERT INTO comboitemtbl (ComboID, MenuID) VALUES (" . $ComboID . ", NULL);");
        mysqli_query($conn, "INSERT INTO comboitemtbl (ComboID, MenuID) VALUES (" . $ComboID . ", NULL);");

    }
    $combo = $conn->query("SELECT * FROM combotbl WHERE ComboID = '" . $ComboID . "';");
    $rowsOfComps = $conn->query("SELECT * FROM comboitemtbl WHERE ComboID = '" . $ComboID . "';");

    $edited = 0;

    while ($row = mysqli_fetch_array($combo)) {
        ?>
        <div class="confirmText">Confirming Edit of Combo ID <mark class="editedText"><?php echo $ComboID; ?></mark></div>
        <?php
        if ($row['ComboName'] !== $ComboName) {
            ?>
            <!--Confirmation for editing the Combo Name.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['ComboName'] ?></mark> to <mark
                    class="editedText"><?php echo $ComboName; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE combotbl 
        SET ComboName='" . $ComboName . "' 
        WHERE ComboID = '" . $ComboID . "';");
            $edited = 1;
        }

        if ($row['Discount'] !== $Discount) {
            ?>
            <!--Confirmation for editing the Discount.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['Discount'] ?></mark> to <mark
                    class="editedText"><?php echo $Discount; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE combotbl 
        SET Discount='" . $Discount . "' 
        WHERE ComboID = '" . $ComboID . "';");
            $edited = 1;
        }

        if ($row['TimesUsed'] !== $TimesUsed) {
            ?>
            <!--Confirmation for editing the Times Used.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['TimesUsed'] ?></mark> to <mark
                    class="editedText"><?php echo $TimesUsed; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE combotbl 
        SET TimesUsed='" . $TimesUsed . "' 
        WHERE ComboID = '" . $ComboID . "';");
            $edited = 1;
        }

        /* Check the rows of components next to our component list. */
        /* If one of our components matches the given row, loop. */
        $i = 1;
        $dbComps = $survComps = [];
        function arrEquality($a1, $a2)
        {
            if (count($a1) !== count($a2))
                return 1;
            for ($i = 0; $i < count($a1); $i++) {
                if ($a1[$i] !== $a2[$i])
                    return 1;
            }
            return 0;
        }

        while ($comps = mysqli_fetch_array($rowsOfComps)) {
            $dbComps[] = $comps['MenuID'];
            $componentKey = 'Component' . $i;
            $survComps[] = $Components[$componentKey];
            //}
            $i++;
        }
        if (arrEquality($dbComps, $survComps) == 1) {
            $entry_exists = 0;
            $qu = "SELECT EXISTS (
                    SELECT 1
                    FROM comboitemtbl
                    WHERE ComboID=" . $ComboID . " AND MenuID IS NOT NULL
                ) AS entry_exists;";
            $res = mysqli_query($conn, $qu);
            if ($res) {
                $row = mysqli_fetch_assoc($res);
                $entry_exists = $row['entry_exists'];
            }
            for ($j = 0; $j < 3; $j++) {
                if ($entry_exists) {
                    mysqli_query($conn, "UPDATE comboitemtbl SET MenuID='" . $survComps[$j] . "' WHERE MenuID='" . $dbComps[$j] . "' AND ComboID='" . $ComboID . "' LIMIT 1;");
                } else {
                    mysqli_query($conn, "UPDATE comboitemtbl SET MenuID='" . $survComps[$j] . "' WHERE MenuID IS NULL AND ComboID='" . $ComboID . "' LIMIT 1;");
                }
                ?>
                <div class="confirmText">
                    Edited: Menu ID [<mark class="editedText"><?php echo $dbComps[$j] ?></mark> to <mark
                        class="editedText"><?php echo $survComps[$j]; ?></mark>]
                </div>
                <?php
            }
            $edited = 1;
        }


        ?>
        <?php
    }

    if ($edited == 0) {
        ?>
        <div class="confirmText">No edit was detected. No changes were made.</div>
        <?php
    }

    ?>
    <br><br>
    <div class="pageButtons">
        <a href="adminComboPage.php" class="toPagesButton">Back</a>
    </div>

</body>

</html>
