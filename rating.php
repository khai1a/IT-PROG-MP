<?php
include("dbConnect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating - Karinderia ni Aling Bebang</title>
    <style>
    @font-face {
        src: url(fonts/Gagalin-Regular.otf);
        font-family: 'gagalin';
    }

    body {
        background-color: #fafaf1;
        margin: 0px;
        font-size: 18px;
    }

    /* top */
    #topbox {
        height: 28vh;
        background-color: #fff2dc;
        border-bottom: 3px solid rgb(114, 113, 113);
        position: relative;
    }

    /* Rating icon */
    #current_display {
        height: 70px;
        width: 150px;
        left: -20px;
        top: 20px;
        background-color: #a15b24;
        border-radius: 20px;
        position: absolute;
        text-align: center;
        padding: 20px;
    }

    #current_display img {
        height: 80px;
    }

    /* aling bebang */
    #aling_bebang {
        background-image: url(images/aling_bebang.PNG);
        background-size: contain;
        background-repeat: no-repeat;
        height: 26vh;
        width: 230px;
        left: 30%;
        position: absolute;
        bottom: 0;
    }

    .typewriter{
        height:18vh;
        width: 43%;
        right: 20px;
        position:absolute;
        align-items: center;
        justify-content: center;
    }

    .typewriter p{
        font-size: 30px;
        top:40px;
        font-family: gagalin;
        overflow: hidden;
        white-space: nowrap;
        position:relative;
        animation: typing 2s;
        color: #cf9829;
    }

    @keyframes typing {
        from { width: 0; }
        to { width: 100%; }
    }

    /* mid */
    #midbox {
        height: 55vh;
        background-color: #e2bea0;
        padding: 10px;
        text-align: center;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    /* star size */
    .stars {
    font-size: 80px;
    cursor: pointer;
    margin-top: 0;
    margin-bottom: 10px;
    }

    .star {
    color: #ccc;
    transition: color 0.3s;
    }

    #comment-box {
    width: 1000px;
    height: 100px;
    font-size: 18px;
    padding: 10px;
    margin-top: 10px;
    }

    label[for="comment-box"] {
    font-size: 24px; 
    font-weight: bold; 
    color: #a15b24;
    font-family: gagalin;
}

    #submit-feedback {
    font-size: 20px;
    padding: 15px 30px;
    background-color: #a15b24;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    }

    #submit-feedback:hover {
    background-color: #cf9829;
    }

    h3 {
    font-size: 40px;
    margin-bottom: 5px;
    margin-top: 15px;
    font-family: gagalin;
    color: #a15b24;
    }

    /* low */
    #lowbox {
        height: 12vh;
        background-color: #a15b24;
        padding: 10px;
        text-align: center;
    }

    /* Home icon */
    #home {
        height: 75px;
        width: 125px;
        position: relative;
        top: 10px;  
    }

    .img_button{
        cursor: pointer;
        position:absolute;
    }

    /* separation image */
    #seperation {
        background-image: url(images/Seperator.png);
        background-size: cover;
        height: 10vh;
        width: 100%;
        position: absolute;
        bottom: 0;
        opacity: 0.75;
    }

    /* music icon */
    #music-logo {
        width: 100px;
        position: absolute;
        bottom: 12px; 
        left: 40px; 
        cursor: pointer;
    }
    </style>
</head>
<body>

<div id="topbox">
    <div id="seperation"></div>
    <div id="current_display">
        <img src="images/rating.png">
    </div>
    <div id="aling_bebang"></div>
    <div id="seperation"></div>
    <div id="conversation" class="typewriter">
        <p><?php echo "Pafeedback naman dyan!"; ?></p>
    </div>
</div>

<div id="midbox">
    <div class="rating-section">
        <h3>Leave a Rating!</h3>
        <div class="stars">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <div>
            <label for="comment-box">Comments/Suggestions:</label><br>
            <textarea id="comment-box" placeholder="Type your comments here..."></textarea>
        </div>
        <br>
        <button id="submit-feedback" onclick="submitFeedback()">Submit</button>
    </div>
</div>

<div id="lowbox">
    <img id="music-logo" class="img_button" src="images/audiooff2.png" alt="Music Logo">

    <a href="Main Menu.html" onclick="saveNullRating(event)">
    <img id="home" class="img_button" src="images/home.png" alt="Home">
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

let selectedRating = 0;
const stars = document.querySelectorAll('.star');

// stars
stars.forEach(star => {
    star.addEventListener('click', () => {
        selectedRating = parseInt(star.getAttribute('data-value'));

        stars.forEach(s => s.style.color = '#ccc');
        for (let i = 0; i < selectedRating; i++) {
            stars[i].style.color = '#FFD700';
        }
    });
});

function submitFeedback() {
    const comment = document.getElementById("comment-box").value.trim();

    if (selectedRating === 0 || isNaN(selectedRating)) {
        alert("Please select a star rating before submitting.");
        return;
    }
    if (comment === "") {
        alert("Please enter a comment before submitting.");
        return;
    }

    fetch('ratingToDB.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `stars=${selectedRating}&comment=${encodeURIComponent(comment)}`
    })
    .then(response => response.text())
    .then(() => fetch('clearCart.php', { method: 'POST' }))
    .then(() => fetch('clearMethods.php', { method: 'POST' }))
    .then(() => {
        console.log("Cart and payment/dining method cleared.");
        alert("Thank you for your feedback!");
        window.location.href = "Main Menu.html";
    })
    .catch(error => console.error("Error:", error));
}

function saveNullRating(event) {
    event.preventDefault();

    fetch('ratingToDB.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `stars=NULL&comment=NULL`
    })
    .then(response => response.text())
    .then(() => fetch('clearCart.php', { method: 'POST' }))
    .then(() => fetch('clearMethods.php', { method: 'POST' }))
    .then(() => {
        console.log("Cart and payment/dining method cleared.");
        window.location.href = "Main Menu.html";
    })
    .catch(error => {
        console.error("Error saving null rating:", error);
        window.location.href = "Main Menu.html";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("back").addEventListener("click", function () {
        localStorage.removeItem("selectedDiningMethod");
        localStorage.removeItem("selectedMethod");
    });
});

</script>

</body>
</html>
