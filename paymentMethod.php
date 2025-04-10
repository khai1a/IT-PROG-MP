<?php
session_start();
include("dbConnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["method"])) {
    $newPaymentMethod = $_POST["method"];
    $_SESSION['paymentMethod'] = $_POST["method"];
    
    // Send success response
    echo json_encode(["success" => true, "message" => "Payment method stored"]);
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Karinderia ni Aling Bebang</title>
    <link rel="stylesheet" href="paymentMethod.css">
</head>
<body>

<div id="topbox">
         <div id="seperation"></div>
        <div id="current_display">
            <img src="images/payment.png">
        </div>
        <div id="aling_bebang"></div>
        <div id="seperation"></div>
        <div id = "conversation" class = "typewriter">
            <p><?php echo "Magbabayad na po?"; ?></p>
        </div>
</div>

<div id="midbox">
    <p>Order Summary</p>
        <div id="order_list">
            <table id="order_table">
             <thead>
            <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
        </div>

        <div class="payment">
            <button data-method="E-Wallet" onclick="selectMethod('E-Wallet')">
                <img src="images/ewallet.png" alt="E-Wallet">
                <p>E-Wallet</p>
            </button>

            <button data-method="Card" onclick="selectMethod('Card')">
                <img src="images/card.png" alt="Card">
                <p>Card</p>
            </button>
        </div>
</div>

<div id="lowbox">
        <img id="music-logo" class="img_button" src="images/audiooff2.png" alt="Music Logo">

        <a href="diningMethod.php">
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
    const isConfirmed = confirm("Are you sure you want to proceed with the payment method: " + method + "?");

    if (isConfirmed) {
        localStorage.setItem("selectedMethod", method);

        document.querySelectorAll(".payment button").forEach(btn => {
            btn.classList.remove("selected");
            if (btn.querySelector("p").innerText.trim() === method) {
                btn.classList.add("selected");
            }
        });

        // Call this only now
        fetch('fetchCart.php', { method: 'GET' })
            .then(response => response.json())
            .then(data => {
                const cart = data.cart;

                fetch('applyCombos.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(cart)
                })
                .then(response => response.json())
                .then(comboData => {
                    let total_order_price = 0;
                    let totalDiscount = 0;

                    cart.forEach(item => {
                        if (item.quantity > 0) {
                            total_order_price += parseFloat(item.price) * parseInt(item.quantity);
                        }
                    });

                    if (comboData.comboUsage) {
                        Object.values(comboData.comboUsage).forEach(combo => {
                            totalDiscount += combo.totalDiscount;
                        });
                    }

                    const total_discounted = (total_order_price - totalDiscount).toFixed(2);

                    sendInfoToBackend(total_order_price, total_discounted);
                });
            });

    } else {
        console.log("Payment method selection canceled.");
    }
}

    


window.onload = function () {
    let selectedMethod = localStorage.getItem("selectedMethod");
    if (selectedMethod) {
        document.querySelectorAll(".payment button").forEach(btn => {
            if (btn.querySelector("p").innerText.trim() === selectedMethod) {
                btn.classList.add("selected");
            }
        });
    }
};

function updateOrderList(cart) {
            const tableBody = document.querySelector('#order_table tbody');
            tableBody.innerHTML = ''; 
            let total_order_price = 0; 

            cart.forEach(item => {
                if (item.quantity > 0) {
                    const totalPrice = (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2); 
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>₱${totalPrice}</td>
                    `;
                    total_order_price += parseFloat(totalPrice);
                    tableBody.appendChild(row);
                }
            });

            fetch('applyCombos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(cart)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Combo Data Received:", data);
                let totalDiscount = 0;
                
                if (data.comboUsage) {
                    Object.values(data.comboUsage).forEach(combo => { 
                        const discountRow = document.createElement('tr');
                        discountRow.innerHTML = `
                            <td colspan="2" style="text-align: right;">${combo.name} (x${combo.timesApplied}):</td>
                            <td style="color: red;">-₱${combo.totalDiscount.toFixed(2)}</td>
                        `;
                        tableBody.appendChild(discountRow);
                        totalDiscount += combo.totalDiscount;
                    });
                }
                
                //Discounted total (total price - total discount)
                let total_discounted = (total_order_price - totalDiscount).toFixed(2);

                const finalTotal = document.createElement('tr');
                finalTotal.innerHTML = `
                    <td colspan="2" style="text-align: right; font-weight: bold;">Final Total:</td>
                    <td style="font-weight: bold;">₱${total_discounted}</td>
                `;
                tableBody.appendChild(finalTotal);

            })
            .catch(error => console.error('Error:', error));
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetchCartAndUpdate();
        });

        function fetchCartAndUpdate() {
            fetch('fetchCart.php', { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    updateOrderList(data.cart);
                })
                .catch(error => console.error('Error:', error));
        }
        
        function sendInfoToBackend(total_order_price, total_discounted) {
    let diningMethod = localStorage.getItem("selectedDiningMethod") || "Unknown";
    let paymentMethod = localStorage.getItem("selectedMethod") || "Unknown";

    fetch('fetchCart.php')
        .then(response => response.json())
        .then(data => {
            const cart = data.cart;

            fetch('saveToDB.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'total_order_price=' + encodeURIComponent(total_order_price) +
                    '&total_discounted=' + encodeURIComponent(total_discounted) +
                    '&payment_method=' + encodeURIComponent(paymentMethod) +
                    '&dining_method=' + encodeURIComponent(diningMethod) +
                    '&cart=' + encodeURIComponent(JSON.stringify(cart))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Saved successfully.");
                    localStorage.removeItem("selectedDiningMethod");
                    localStorage.removeItem("selectedMethod");
                    window.location.href = "rating.php"; // <-- only redirect now
                } else {
                    console.error("Save failed.");
                }
            });
        });
}

</script>

</body>
</html>