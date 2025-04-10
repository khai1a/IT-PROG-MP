<?php
include("dbConnect.php");
$menu_query = "SELECT * FROM menutbl";
$menu_result = $conn->query($menu_query);

function addLogos($AllergenPic,$spicy,$id){
    if ($spicy == 1) {
        echo "<img id = 'spicy_$id' img class='logo_spicy' src='images/spicy.png' alt='Spicy'>";
    }
    if (!empty($AllergenPic)) {
        echo "<img id = 'allergen_$id' img class='logo_allergen' src='$AllergenPic' alt='AllergenPic'>";
    }
}

$best_selling_query = "
    SELECT m.*, SUM(o.qty) as total_sold
    FROM menutbl m
    JOIN ordertbl o ON m.MenuID = o.MenuID
    GROUP BY m.MenuID
    ORDER BY total_sold DESC
    LIMIT 10;
";
$best_selling_result = $conn->query($best_selling_query);

$best_drink = null;
$best_side = null;
$best_main = null;

while ($row = $best_selling_result->fetch_assoc()) {
    if ($row['IsMeal'] == 0 && !$best_drink) {
        $best_drink = $row;
    } elseif ($row['IsMeal'] == 2 && !$best_side) {
        $best_side = $row;
    } elseif ($row['IsMeal'] == 1 && !$best_main) {
        $best_main = $row;
    }
}


$best_combo_query = "
    SELECT * FROM combotbl
    ORDER BY TimesUsed DESC
    LIMIT 1;
";
$best_combo_result = $conn->query($best_combo_query);
$best_combo = $best_combo_result->fetch_assoc();

$combo_items = [];
if ($best_combo) {
    $combo_items_query = "
        SELECT m.*
        FROM comboitemtbl c
        JOIN menutbl m ON c.MenuID = m.MenuID
        WHERE c.ComboID = {$best_combo['ComboID']}
    ";
    $combo_items_result = $conn->query($combo_items_query);
    while ($item = $combo_items_result->fetch_assoc()) {
        $combo_items[] = $item;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karinderia ni Aling Bebang</title>
    <link rel="stylesheet" href="Best Sellers.css">
</head>

<body>


    <div id = "topbox">
        <div id = "current_display">
            <img src="images/question_mark.png">
        </div>
        <div id ="aling_bebang"></div>
        <div id = "seperation"></div>
        <div id = "conversation" class = "typewriter">
            <p>Eto yung mga best seller natin!</p>
        </div> 
    </div>

    <div id= "midbox">
        <?php
            echo "<div id='left_side'>";
            echo "<h2>Best Selling Drink, Side & main </h2>";
            if ($best_drink) {
                echo "<div class='drinks'>
                <img src='{$best_drink['ItemPic']}' class='item_button_drinks' alt='{$best_drink['ItemName']}'>
                </div>";
            }
            if ($best_side) {
                echo "<div class='main_sides'>
                <img src='{$best_side['ItemPic']}' class='item_button_main_sides' alt='{$best_side['ItemName']}'>
                </div>";
            }
            if ($best_main) {
                echo "<div class='main_sides'>
                <img src='{$best_main['ItemPic']}' class='item_button_main_sides' alt='{$best_main['ItemName']}'>
                </div>";
            }
            echo "</div>";
            echo "<div id='separator'></div>";

            if ($best_combo) {
                echo "<div id='right_side'>";
                echo "<h2>Best Combo: {$best_combo['ComboName']}</h2>";
                foreach ($combo_items as $item) {
                    
                    $tray_class = ($item['IsMeal'] == 0) ? "drinks" : "main_sides";
                    $img_class = ($item['IsMeal'] == 0) ? "item_button_drinks" : "item_button_main_sides";
    
                    echo "<div class='$tray_class'>
                        <img src='{$item['ItemPic']}' class='$img_class' alt='{$item['ItemName']}'>
                    </div>";
                }
                echo "</div>";
            }
        ?>
    </div>

    <div id= "lowbox">
        <a href = "Order Menu.php">
        <img id= "back" class = "img_button"  src = "images/back.png" alt="Back">
        </a>
    </div>

</body>
</html>