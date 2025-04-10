<?php
session_start();

$_SESSION['diningMethod'] = null;
$_SESSION['paymentMethod'] = null;

echo "<script>
    localStorage.removeItem('selectedDiningMethod');
    localStorage.removeItem('selectedMethod');
    window.location.href = 'Main Menu.html';
</script>";
?>
