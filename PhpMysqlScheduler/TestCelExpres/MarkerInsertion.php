<?php include "dbconn/conn.php"; ?>
<?php
$csv = array_map('str_getcsv', file($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/MarkerFile.csv"));
//$csv = array_map('str_getcsv', file($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/MarkerFile.csv"));
for($i=1;$i<count($csv);$i++) {
    $col="";
    $val="";
    for($j=0;$j<count($csv[$i]);$j++) {
        switch ($j) {
            case 0:
                $col.="MarkerName,";
                $val.='\''.$csv[$i][$j].'\',';
                //echo''.$col.'</br>'.$csv[$i][$j].'';
                break;
            case 1:
                $col.="MarkerSurname,";
                $val.='\''.$csv[$i][$j].'\',';
                //echo''.$col.'</br>'.$csv[$i][$j].'';
                break;
            case 2:
                $col.="MarkerEmail";
                $val.='\''.$csv[$i][$j].'\'';
                //echo''.$col.'</br>'.$csv[$i][$j].'';
                break;
            default:
                $col.="";
                $val.="";
        }
    }
    $val = preg_replace("/\s|&nbsp;/",' ',$val);
    $sql = " INSERT INTO marker (".$col.") VALUES (".$val.") ";
    if ($conn->query($sql) === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

//echo '<pre>'; print_r($csv); echo '</pre>';
}

?>
