<?php
if(isset($_POST['logoutBtn'])){
    session_start();
    if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
        header("Location: SetBusiness.php");
    }else{
        unset($_SESSION['buID']);
        unset($_SESSION['businessCode']);
        unset($_SESSION['uStatus']);
        unset($_SESSION['buCode']);
        unset($_SESSION['buDesc']);
        unset($_SESSION['perdoruesi']);
        unset($_SESSION['fjalekalimi']);
        unset($_SESSION['CID']);
        unset($_SESSION['date1']);
        unset($_SESSION['date2']);
        session_destroy();
        header("Location: index.php");
    }
}
if(isset($_POST['logoutBtnAdmin'])){
    unset($_SESSION['buID']);
    unset($_SESSION['businessCode']);
    unset($_SESSION['uStatus']);
    unset($_SESSION['buCode']);
    unset($_SESSION['buDesc']);
    unset($_SESSION['perdoruesi']);
    unset($_SESSION['fjalekalimi']);
    unset($_SESSION['CID']);
    unset($_SESSION['date1']);
    unset($_SESSION['date2']);
    session_destroy();
    header("Location: index.php");
}
?>
