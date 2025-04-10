<html>

<head>
    <title>Delete Confirmation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['MenuID'])) {
            $deleteMe = $_POST['MenuID'];
            $nukeFrom = "menutbl";
            $IDName = "MenuID";
            $redirectTo = "adminMenuPage.php";
        } else if (isset($_POST['ComboID'])) {
            $deleteMe = $_POST['ComboID'];
            $nukeFrom = "combotbl";
            $IDName = "ComboID";
            $redirectTo = "adminComboPage.php";
        } else if (isset($_POST['SalesID'])) {
            $deleteMe = $_POST['SalesID'];
            $nukeFrom = "salestbl";
            $IDName = "SalesID";
            $redirectTo = "adminSales.php";
        } else if (isset($_POST['RatingID'])) {
            $deleteMe = $_POST['RatingID'];
            $nukeFrom = "ratingtbl";
            $IDName = "RatingID";
            $redirectTo = "adminRatings.php";
        } else if (isset($_POST['OrderID'])) {
            $deleteMe = $_POST['OrderID'];
            $nukeFrom = "ordertbl";
            $IDName = "OrderID";
            $redirectTo = "adminSales.php";
        }
        include 'dbConnect.php';
        $sq = "DELETE FROM ordertbl WHERE SalesID = '".$deleteMe."';"; //Sales-specific deletion query.
        $cq = "DELETE FROM comboitemtbl WHERE ComboID ='".$deleteMe."';"; //Combo-specific deletion query.
        $q = "DELETE FROM ".$nukeFrom." WHERE ".$IDName." = '".$deleteMe."';";
        /* This makes sure that we only delete anything related to the comboitemtbl if we're also deleting the associated combo afterward. */
        if ($nukeFrom === "combotbl") {
            $deletedComboItems = $conn->query($cq);
        }
        /* This makes sure that we only delete anything related to the ordertbl if we're also deleting the associated sale afterward. */
        if ($nukeFrom === "salestbl") {
            $deletedSalesItems = $conn->query($sq);
        }
        $deleted = $conn->query($q);
        
    }
    ?>
    <div class="confirmText">
        Entry <?php echo $IDName . " #" . $deleteMe ?> has been deleted. Redirecting...
    </div>

    <script>
        function redirecting() {
            window.location.href = "<?php echo $redirectTo; ?>";
        }
        setTimeout(redirecting, 5000);
    </script>
</body>

</html>
