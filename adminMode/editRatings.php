<html>

<head>
    <title>Edit Rating</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $RatingID = $_POST['RatingID'];
        $Stars = $_POST['Stars'];
        $Comment = $_POST['Comment'];
    }
    ?>

    <form action="confirmRatings.php" method="POST" enctype="multipart/form-data">
        <label>ID:</label>
        <input disabled="disabled" type="text" <?php if (isset($RatingID)) { ?> value="<?php echo $RatingID;
        } ?>" readonly><br><br>
        <input type="hidden" name="RatingID" <?php if (isset($RatingID)) { ?> value="<?php echo $RatingID;
        } ?>">

        <label>Stars</label>
        <input type="number" name="Stars" <?php if (isset($Stars)) { ?> value="<?php echo $Stars;
        } ?>"><br><br>

        <label>Comment</label>
        <textarea cols="50" rows="8" type="text" name="Comment" maxlength="400"><?php if (isset($Comment)) {
            echo $Comment;
        } ?></textarea><br><br>

        <div class="pageButtons">
            <button type="submit" class="confirmButton">Done?</button>
        </div>

    </form>
    <br>

    <div class="pageButtons">
        <a href="adminRatings.php" class="toPagesButton">Back</a>
    </div>
</body>

</html>
