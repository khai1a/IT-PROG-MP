<html>

<head>
    <title>Rating Deletion</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $RatingID = $_POST['RatingID'];
    }

    include "dbconnect.php";
    $ratingRow = $conn->query("SELECT * FROM ratingtbl WHERE RatingID=" . $RatingID);
    ?>
    <div class="confirmText">
        Are you sure you want to delete this Ratings entry?
        <table class="table">
            <tr class="mainRow">
                <?php
                while ($row = mysqli_fetch_array($ratingRow)) { ?>
                    <td> <?php echo $row['RatingID']; ?> </td>
                    <td> <?php echo $row['Stars']; ?> </td>
                    <td> <?php echo $row['Comment']; ?> </td>
                <?php } ?>
            </tr>
        </table>
    </div>
    <div class="delButtonLine pageButtons">
        <form method="POST" action="seniorDelete.php">
            <input type="hidden" name="RatingID" value="<?php echo $RatingID?>">
            <button type="submit" class="confirmButton">Delete</button>
            <a href="adminRatings.php" class="toPagesButton">Cancel</a>
        </form>
    </div>

</body>

</html>
