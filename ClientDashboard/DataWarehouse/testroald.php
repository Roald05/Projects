<html>
<body>
<form method="post" action="testroald.php">
    Seriali
    <input type="text" name="seriali">
    <input type="submit" name="sub">
</form>
</body>
</html>


<?php
if(isset($_POST["sub"])){
    $input = $_POST["seriali"];
    $key = "roald";

    echo  "free text= " . $input. "</br>";;

    $key = hash("md5", $key, true);
    echo  "md5 hash= " . $key. "</br>";

    $encrypted = openssl_encrypt ( $input , "des-ede" , $key) ;
    echo  "encrypted text= " .$encrypted . "</br>";

    $encrypted = openssl_decrypt( $encrypted , "des-ede" , $key) ;
    echo  "decrypted text= " .$encrypted . "</br>";
}
?>