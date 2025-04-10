<html>

<head>
    <title>Rating Edit Confirmation</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <!--This will come directly from the editRatings.php form.-->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $RatingID = $_POST['RatingID'];
        $Stars = $_POST['Stars'];
        $Comment = $_POST['Comment'];

    }

    include 'dbConnect.php';
    $ratingsRow = $conn->query("SELECT * FROM ratingtbl WHERE RatingID = '".$RatingID."';");

    while ($row = mysqli_fetch_array($ratingsRow)) {
        ?>
        <div class="confirmText">Confirming Edit of Rating ID <mark class="editedText"><?php echo $RatingID; ?></mark></div>
        <?php
        if ($row['Stars'] !== $Stars) {
            ?>
            <!--Confirmation for editing the Stars.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['Stars'] ?></mark> to <mark class="editedText"><?php echo $Stars; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE ratingtbl 
            SET Stars='" . $Stars . "' 
            WHERE RatingID = '" . $RatingID . "';");
        }
        if ($row['Comment'] !== $Comment) {
            ?>
            <!--Confirmation for editing the Comment.-->
            <div class="confirmText">
                Edited: [<mark class="editedText"><?php echo $row['Comment'] ?></mark> to <mark
                    class="editedText"><?php echo $Comment; ?></mark>]
            </div>
            <?php
            mysqli_query($conn, "UPDATE ratingtbl 
            SET Comment='" . $Comment . "' 
            WHERE RatingID = '" . $RatingID . "';");
        }
        if ($row['Stars'] == $Stars && $row['Comment'] == $Comment) {
            ?>
            <div class="confirmText">No edit was detected. No changes were made.</div>
            <?php
        }
    }
    ?>
    <br><br>
    <div class="pageButtons">
        <a href="adminRatings.php" class="toPagesButton">Back</a>
    </div>

</body>

</html>
