<?php
session_start();

if(isset($_GET['currency'])){
    $_SESSION['currency'] = $_GET['currency'];
}

// Redirect back to previous page or home page
if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: index.php");
}
exit();
?>