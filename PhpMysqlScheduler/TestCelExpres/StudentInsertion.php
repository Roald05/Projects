<?php include "dbconn/conn.php"; ?>
<?php
$csv = array_map('str_getcsv', file($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/StudentFile.csv"));
//$csv = array_map('str_getcsv', file($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/StudentFile.csv"));
$studentMod=array();
for($i=1;$i<count($csv);$i++) {
    $col="";
    $val="";
    for($j=0;$j<count($csv[$i]);$j++) {
        switch ($j) {
            case 0:
                $col.="StudentName,";
                $val.='\''.$csv[$i][$j].'\',';
                //echo''.$col.'</br>'.$csv[$i][$j].'';
                break;
            case 1:
                $col.="StudentSurname,";
                $val.='\''.$csv[$i][$j].'\',';
                //echo''.$col.'</br>'.$csv[$i][$j].'';
                break;
            case 2:
                $col.="StudentEmail,";
                $val.='\''.$csv[$i][$j].'\',';
                //echo''.$col.'</br>'.$csv[$i][$j].'';
                break;
            case 3:
                $col.="SupervisorName,";
                $val.='\''.$csv[$i][$j].'\',';

                //echo''.$col.'</br>'.$csv[$i][$j].'';

                $supCredentials=explode(" ",$csv[$i][$j]);
                $sqlSupId= "SELECT SupervisorId FROM supervisors WHERE SupervisorName='".$supCredentials[0]."' AND SupervisorSurname='".$supCredentials[1]."'";
                $result = $conn->query($sqlSupId);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $supID=$row["SupervisorId"];
                        $col.="SupervisorId,";
                        $val.=''.$supID.',';
                    }
                }

                break;
            case 4:
                $col.="MarkerName,";
                $val.='\''.$csv[$i][$j].'\',';
                //echo''.$col.'</br>'.$csv[$i][$j].'';

                $markCredentials=explode(" ",$csv[$i][$j]);

                $sqlMarkId= "SELECT MarkerId FROM marker WHERE MarkerName='".$markCredentials[0]."' AND MarkerSurname='".$markCredentials[1]."'";
                $result = $conn->query($sqlMarkId);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $markID=$row["MarkerId"];
                        $col.="MarkerId,";
                        $val.=''.$markID.',';
                    }
                }
                break;
            case 5:
                $studentMod[$i]=explode(';',''.$i.';'.$csv[$i][$j].'');
                break;
            default:
                $col.="";
                $val.="";
        }
    }
    $col.="CollectionIndex";
    $val.='\''.$i.'\'';

    $val = preg_replace("/\s|&nbsp;/",' ',$val);
    $sql = " INSERT INTO students (".$col.") VALUES (".$val.") ";
    if ($conn->query($sql) === false) {
        //echo "Error: INSERT INTO students " . $sql . "<br>" . $conn->error;
    }

}

//fill StudentModule
for($i=1;$i<count($studentMod);$i++) {
    
    for($j=1;$j<count($studentMod[$i]);$j++) {
        
        $sql = " INSERT INTO studentsmodule (StudentId,ModuleCode) VALUES (".$studentMod[$i][0].",'".strtoupper($studentMod[$i][$j])."') ";
                if ($conn->query($sql) === false) {
                    echo "Error: INSERT INTO students " . $sql . "<br>" . $conn->error;
                }
        
        //echo''.strtoupper($studentMod[$i][$j]).' '.$modID.''.'</br>';
    }
}



?>
