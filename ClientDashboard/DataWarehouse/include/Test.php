<?php
function dec($encryptedString){
    //$encrypted=$_POST["licenceID"];
    $key = "roald";
    //echo  "free text= " . $input. "</br>";;

    $key = hash("md5", $key, true);
    //echo  "md5 hash= " . $key. "</br>";

    $encrypted = openssl_decrypt( $encryptedString , "des-ede" , $key) ;
    //echo  "decrypted text= " .$encrypted . "</br>";
    return $encrypted;
}
?>