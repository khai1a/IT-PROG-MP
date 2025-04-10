<?php
include("dbConnect.php");

$menu_query = "SELECT * FROM menutbl";
$menu_result = $conn->query($menu_query);

$conversation = ($menu_result->num_rows > 0) ? "Pili ka na, andaming ulam dito!" : "Wala pa akong tinda ngayon bhie...";

function addLogos($AllergenPic,$spicy,$id){
    if ($spicy == 1) {
        echo "<img id = 'spicy_$id' img class='logo_spicy' src='images/spicy.png' alt='Spicy'>";
    }
    if (!empty($AllergenPic)) {
        echo "<img id = 'allergen_$id' img class='logo_allergen' src='$AllergenPic' alt='AllergenPic'>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karinderia ni Aling Bebang</title>
    <link rel="stylesheet" href="Order Menu.css">
</head>

<body>


    <div id = "topbox">
        <div id = "current_display">
            <img src="images/shopping_cart.png">
        </div>
        <div id ="aling_bebang"></div>
        <div id = "seperation"></div>
        <div id = "conversation" class = "typewriter">
            <p><?php echo $conversation; ?></p>
        </div>
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
    </div>

    <div id= "midbox">
    <?php
        if ($menu_result->num_rows > 0) {
            while ($row = $menu_result->fetch_assoc()) {
                $id = $row['MenuID'];
                $name = $row['ItemName'];
                $image = $row['ItemPic']; 
                $category = $row['IsMeal']; 
                $spicy = $row['Spicy'];
                $AllergenPic = $row['AllergenPic'];
                $price = $row['Price']; 
                $isActive = $row['IsActive'];

                if($isActive == 1)
                {
                    $flipCardWidth = ($category == 0) ? '175px' : '350px';

                    echo "<div id='tray_$id' class='flip-card' style='width: $flipCardWidth; height: 210px;'>";
                    echo "  <div class='flip-card-inner'>";
    
                    
                    echo "    <div class='flip-card-front'>";

                    if($category == 1 || $category == 2)
                    {
                        echo "<div id='tray_$id' class='main_sides'>";
                        echo "<img id='item_$id' class='item_button_main_sides' src='$image' alt='$name'>";
                        addLogos($AllergenPic,$spicy,$id);
                        echo "</div>";
                    }
                    if($category == 0)
                    {
                        echo "<div id='tray_$id' class='drinks'>";
                        echo "<img id='item_$id' class='item_button_drinks' src='$image' alt='$name'>";
                        addLogos($AllergenPic,$spicy,$id);
                        echo " </div>";
                    }
                    echo " </div>";
    
                    
                    echo " <div class='flip-card-back'>";
                    echo " <p>$name</p>";
                    echo " <p>Price: ₱ $price</p>";
                    echo " <label for='quantity_$id'>Quantity:</label>";
                    echo " <input type='number' id='quantity_$id' value='1' min='0' style = 'width: 50px'>";
                    echo " <br><br>";
                    echo " <button class='add-to-cart' data-id='$id' data-name='$name'>Add to Cart</button>";
                    echo " <button class='exit-flip' data-id='$id'>Cancel</button>";
                    echo " </div>";
            
                    echo " </div>";
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
    
    
    <div id= "lowbox">
        <img id="music-logo" class = "img_button" src="images/audiooff2.png" alt="Music Logo"> 
	<a href = "Best Sellers.php"> 
        <img id= "best_seller" class = "img_button"  src = "images/question_mark.png" alt="Best Seller">
	</a>

        <a href = "combos.php">
        <img id= "view_combos" class = "img_button"  src = "images/view_combos.png" alt="View Combos">
        </a>

        <a href = "diningMethod.php">
        <img id= "payment" class = "img_button"  src = "images/payment.png" alt="Payment">
        </a>

        <a href = "Main Menu.html">
        <img id= "back" class = "img_button"  src = "images/back.png" alt="Back">
        </a>

    </div>

    <audio id="bg-music">
        <source src="music/Bg Music.mp3">
    </audio>


    <script>
        let song = document.getElementById("bg-music");
        let music_logo = document.getElementById("music-logo");
    
        music_logo.addEventListener("click", function () {
            if (song.paused) {
                music_logo.src ="images/audio2.png";
                song.volume = 0.05;
                song.play();
            } else {
                music_logo.src ="images/audiooff2.png";
                song.pause();
            }
        });


        document.getElementById('midbox').addEventListener('click', function(event) {
            if (event.target.classList.contains('item_button_main_sides') || event.target.classList.contains('item_button_drinks')) {
                var itemId = event.target.id.replace("item_", ""); 
                const alingBebang = document.getElementById('aling_bebang');
                alingBebang.style.animation = 'none';
                alingBebang.offsetHeight;
                alingBebang.style.animation = 'bounce 0.35s ease-in-out';
                updateConversation(itemId);
            }


            const item = event.target.closest('.flip-card');
            if (item) {
               
                if (event.target.classList.contains('item_button_main_sides') || 
                    event.target.classList.contains('item_button_drinks')) {
                    item.classList.toggle('flipped'); 
                }

                
                if (event.target.classList.contains('exit-flip')) {
                    item.classList.remove('flipped');
                }

                if (event.target.classList.contains('add-to-cart')) {
                    const itemId = event.target.getAttribute('data-id');
                    const itemName = event.target.getAttribute('data-name');
                    const quantity = document.getElementById(`quantity_${itemId}`).value;

                    fetch('addToCart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `itemId=${itemId}&itemName=${encodeURIComponent(itemName)}&quantity=${quantity}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(`Added ${quantity}x ${itemName} to your cart!`);
                            updateOrderList(data.cart);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
            
        });

        document.getElementById('payment').addEventListener('click', function (event) {
        event.preventDefault(); // Prevents immediate redirection

                fetch('fetchCart.php', { method: 'GET' })
                .then(response => response.json())
                .then(data => {
                    if (data.cart.length === 0) {
                        alert("Your cart is empty! Please add items before proceeding to payment.");
                    } 
                    else {
                        window.location.href = "diningMethod.php"; // Redirect if cart is not empty
                    }   
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('back').addEventListener('click', function() {
                fetch('clearCart.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Cart cleared!');
                        updateOrderList([]); 
                    }
                })
                .catch(error => console.error('Error:', error));
            });


        function updateConversation(itemId) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "getConversation.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status == 200) {
                    let conversationDiv = document.getElementById("conversation");
                    
                    conversationDiv.innerHTML = `<p>${xhr.responseText}</p>`;
                }
            };
            xhr.send("itemId=" + itemId);
        }

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
                
                const finalTotal = document.createElement('tr');
                finalTotal.innerHTML = `
                    <td colspan="2" style="text-align: right; font-weight: bold;">Final Total:</td>
                    <td style="font-weight: bold;">₱${(total_order_price - totalDiscount).toFixed(2)}</td>
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


    </script>

</body>
</html>