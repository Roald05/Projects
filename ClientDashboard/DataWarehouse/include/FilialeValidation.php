<?php
if(!isset($_SESSION['CID']) || !isset($_SESSION['date1']) || !isset($_SESSION['date2'])){
    header("Location: Filialet.php");
}
?>