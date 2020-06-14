<?php
include 'dbpass.php';


$emri_db ='db';

$conn=mysqli_connect($host,$user,$password);

if (!$conn) {
    $message = "Nuk u krijua lidhja  me serverin.Ju lutem provoni perseri me vone. ";
    echo "$message<br>";
    die();
}

$db=mysqli_select_db($conn,$emri_db)
or die ("Nuk eshte selektuar databaza.");
?>