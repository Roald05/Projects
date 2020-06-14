<?php
if(isset($_SESSION['uStatus'])){
    if($_SESSION['uStatus']!='A' && $_SESSION['uStatus']!='S'){
        unset($_SESSION['buCode']);
        unset($_SESSION['buDesc']);
        unset($_SESSION['uStatus']);
        unset($_SESSION['perdoruesi']);
        unset($_SESSION['fjalekalimi']);
        unset($_SESSION['CID']);
        unset($_SESSION['date1']);
        unset($_SESSION['date2']);
        session_destroy();
        header("Location: Filialet.php");
    }
}
?>