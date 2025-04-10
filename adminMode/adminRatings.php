<html>

<head>
    <title>Ratings Overview</title>
    <link rel="stylesheet" href="adminLooks.css">
</head>



<body>
    <?php
    include "dbconnect.php";
    $ratings = $conn->query("SELECT * FROM ratingtbl");
    ?>
    <table class="table">
        <tr>
            <th class="th">Rating ID</th>
            <th class="th">Stars</th>
            <th class="th">Comment</th>
            <th class="th">Edit or Delete</th>
        </tr>
        <?php
        //This menu is going to display all given ratings.
        
        while ($row = mysqli_fetch_array($ratings)) {
            ?>
            <tr class="mainRow">
                <td> <?php echo $row['RatingID']; ?> </td>
                <td> <?php echo $row['Stars']; ?> </td>
                <td> <?php echo $row['Comment']; ?> </td>
                <td>
                    <table class="widgetTable">
                        <tr>
                            <form action="editRatings.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="RatingID" value="<?php echo $row['RatingID']; ?>">
                                <input type="hidden" name="Stars" value="<?php echo $row['Stars']; ?>">
                                <input type="hidden" name="Comment" value="<?php echo $row['Comment']; ?>">
                                <button type="submit" class="editButton">Edit</button>
                            </form>
                        </tr>
                        <br>
                        <tr>
                            <form action="deleteRatings.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="RatingID" value="<?php echo $row['RatingID']; ?>">
                                <button type="submit" class="deleteButton">Delete</button>
                            </form>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php } ?>
        <br>
    </table>

    <!--This table deliberately does not have a CREATE button.-->
    <!--LINKS to future pages go here.-->
    <br> <br>
    <div class="pageButtons">
        <a href="adminMenuPage.php" class="toPagesButton">Go to Menu Items</a>
        <a href="adminComboPage.php" class="toPagesButton">Go to Combos</a>
        <a href="adminSales.php" class="toPagesButton">Go to Sales</a>
        <a href="adminNavigator.html" class="toPagesButton">Go Home</a>
    </div>
</body>


</html>
