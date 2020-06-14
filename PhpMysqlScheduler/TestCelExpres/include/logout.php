<?php
if(isset($_POST['logoutBtn'])){
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['schedulerBtn']);
    session_destroy();
    header('Location: index.php');
}
?>
