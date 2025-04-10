<?php
session_start();
include("dbConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["method"])) {
    $newDiningMethod = $_POST["method"];
    $_SESSION['diningMethod'] = $newDiningMethod;  // Store the method in the session
    
    // Redirect to paymentMethod.php
    header("Location: paymentMethod.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dining Method - Karinderia ni Aling Bebang</title>
    <link rel="stylesheet" href="diningMethod.css">
</head>
<body>

<div id="topbox">
    <div id="seperation"></div>
    <div id="current_display">
        <img src="images/dinemtd.png">
    </div>
    <div id="aling_bebang"></div>
    <div id="seperation"></div>
    <div id="conversation" class="typewriter">
        <p><?php echo "Saan ka kakain nhak?"; ?></p>
    </div>
</div>

<div id="midbox">
    <div class="dine-method">
        <button onclick="selectMethod('Dine In')">
            <img src="images/dinein.png" alt="Dine In">
            <p>Dine In</p>
        </button>

        <button onclick="selectMethod('Take Out')">
            <img src="images/takeout.png" alt="Take Out">
            <p>Take Out</p>
        </button>
    </div>
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

// Check if music was playing before
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

function selectMethod(method) {
    // Save the selected dining method in localStorage
    localStorage.setItem("selectedDiningMethod", method); 

    // Remove 'selected' class from all buttons
    document.querySelectorAll(".dine-method button").forEach(btn => {
        btn.classList.remove("selected");
    });

    // Find the selected button and highlight it
    document.querySelectorAll(".dine-method button").forEach(btn => {
        if (btn.querySelector("p").innerText.trim() === method) {
            btn.classList.add("selected");
        }
    });

    // Submit the form to store the selected dining method
    let form = document.createElement("form");
    form.method = "POST";
    form.action = "diningMethod.php";

    let input = document.createElement("input");
    input.type = "hidden";
    input.name = "method";
    input.value = method;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

window.onload = function () {
    // Fetch the dining method from localStorage and highlight the button
    let selectedDiningMethod = localStorage.getItem("selectedDiningMethod");
    if (selectedDiningMethod) {
        document.querySelectorAll(".dine-method button").forEach(btn => {
            if (btn.querySelector("p").innerText.trim() === selectedDiningMethod) {
                btn.classList.add("selected");
            }
        });
    }
};
</script>

</body>
</html>