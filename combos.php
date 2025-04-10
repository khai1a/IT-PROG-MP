<?php
include("dbConnect.php");

$menu_query = "SELECT * FROM menutbl WHERE IsActive = 1";
$menu_result = $conn->query($menu_query);

$combo_query = "SELECT c.ComboID, c.ComboName, c.Discount
                FROM combotbl c
                WHERE NOT EXISTS (
                    SELECT 1 
                    FROM comboitemtbl ci
                    WHERE ci.ComboID = c.ComboID 
                    AND ci.MenuID NOT IN (
                        SELECT MenuID FROM menutbl WHERE IsActive = 1
                    )
                )";
$combo_result = $conn->query($combo_query);

$selectedCombo = null;
if (isset($_GET['comboID'])) {
    $selectedCombo = intval($_GET['comboID']);
}

$comboItems = [];
$comboDiscount = 0;
$comboName = "";

if ($selectedCombo) {
    $combo_item_query = "SELECT ci.MenuID, c.ComboName, c.Discount
                         FROM comboitemtbl ci
                         JOIN combotbl c ON ci.ComboID = c.ComboID
                         WHERE ci.ComboID = ?";
    $stmt = $conn->prepare($combo_item_query);
    $stmt->bind_param("i", $selectedCombo);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $comboItems[] = $row['MenuID'];
        $comboName = $row['ComboName'];
        $comboDiscount = $row['Discount'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combos - Karinderia ni Aling Bebang</title>
    <link rel="stylesheet" href="combos.css">
</head>
<body>

    <div id="topbox">
        <div id="seperation"></div>
        <div id="current_display">
            <img src="images/view_combos.png">
        </div>
        <div id="aling_bebang"></div>
        <div id="seperation"></div>
        <div id="conversation">
            <p>
            <?php
                if ($combo_result->num_rows > 0) {
                    if ($selectedCombo) {
                        echo "Para makuha ang <b>$comboName</b> combo, kailangan mong umorder ng mga ito.";
                        echo "<br>Makakakuha ka ng ₱$comboDiscount discount!";
                    } else {
                        echo "Eto ang mga combos na meron, bhie! I-click mo para tignan kung anong items ang kailangan.";
                    }
                } else {
                    echo "Sorry beh, wala kaming combos na available ngayon huhu.";
                }
            ?>
            </p>
        <div id="combo-list">
            <?php
                if ($combo_result->num_rows > 0) {
                    while ($combo = $combo_result->fetch_assoc()) {
                        $class = "";
                        if ($selectedCombo == $combo['ComboID']) {
                            $class = "selected";
                        }
                
                        echo '<a href="combos.php?comboID=' . $combo['ComboID'] . '" 
                              class="combo-link ' . $class . '">'
                              . htmlspecialchars($combo['ComboName']) . 
                             '</a>';
                    }
                } else {
                    echo '<p>------------</p>';
                }
            ?>
            </div>
        </div>
    </div>

    <div id="midbox">
        <?php
        if ($menu_result->num_rows > 0) {
            while ($row = $menu_result->fetch_assoc()) {
                $id = $row['MenuID'];
                $name = $row['ItemName'];
                $image = $row['ItemPic']; 
                $category = $row['IsMeal'];
                $glowClass = "";
        
                if (in_array($id, $comboItems)) {
                    $glowClass = "glowing";
                }
        
                $categoryClass = "";
                if ($category == 0) {
                    $categoryClass = "drinks";
                } else {
                    $categoryClass = "main_sides";
                }
        
                echo "<div id='tray_$id' class='tray-container'>";
                echo "<div class='tray-image'>";
                echo "<div class='$categoryClass $glowClass'>";
                echo "<img id='item_$id' src='$image' alt='$name'>";
                echo "<p class='item-name'>$name</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }        
        ?>
    </div>

    <div id="lowbox">
        <img id="music-logo" class="img_button" src="images/audiooff2.png" alt="Music Logo">

        <a href="Order Menu.php">
            <img id="back" class="img_button" src="images/back.png" alt="Back">
        </a>
    </div>

    <audio id="bg-music">
    <source src="music/Bg Music.mp3">
</audio>

<script>
    let audio = document.getElementById("bg-music");
    let logo = document.getElementById("music-logo");

    if (localStorage.getItem("musicPlaying") === "true") {
        audio.volume = 0.05;
        audio.play();
        logo.src = "images/audio2.png";
    }

    logo.addEventListener("click", function () {
        if (audio.paused) {
            audio.volume = 0.05;
            audio.play();
            logo.src = "images/audio2.png";
            localStorage.setItem("musicPlaying", "true"); 
        } else {
            audio.pause();
            logo.src = "images/audiooff2.png";
            localStorage.setItem("musicPlaying", "false"); 
        }
    });
</script>
</body>
</html>
