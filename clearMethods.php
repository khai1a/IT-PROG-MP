<?php
session_start();

$_SESSION['diningMethod'] = null;
$_SESSION['paymentMethod'] = null;

echo "<script>
    localStorage.removeItem('selectedDiningMethod');
    localStorage.removeItem('selectedMethod');
    // Optionally clear all localStorage items if necessary:
    // localStorage.clear();

    // Redirect to the main menu after clearing the session and localStorage
    window.location.href = 'Main Menu.html';
</script>";
?>