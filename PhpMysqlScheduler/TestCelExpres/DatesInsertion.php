<?php include "dbconn/conn.php"; ?>
<?php
//get timetable content array
if(isset($_POST['arr'])){
    $arr = json_decode($_POST['arr'], true);;
    $arr=array_filter($arr);
    //echo '<pre>'; print_r($arr); echo '</pre>';
    
    $conn->begin_transaction();
$flag=false;

//fill activity
for($i=0;$i<count($arr);$i++) {
    $col="";
    $val="";
    for($j=0;$j<count($arr[$i]);$j++) {
        switch ($j) {
            case 0:
                $col.="Day,";
                $val.='\''.strtoupper($arr[$i][$j]).'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 1:
                $col.="StartTime,";
                $val.='\''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 2:
                $col.="FinishTime,";
                $val.='\''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 3:
                $col.="Weeks,";
                $val.='\''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 4:
                $col.="ModuleCode,";
                $val.='\''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 5:
                $col.="ActivityName,";
                $val.='\''.trim($arr[$i][$j]).'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 6:
                $col.="Description,";
                $val.='\''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 8:
                $col.="NoStudents,";
                $val.='\''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 9:
                $col.="Room,";
                $val.='\''.$arr[$i][$j].'';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 10:
            case 12:
                $val.=''.$arr[$i][$j].'\',';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            case 11:
                $col.="Lecturer,";
                $val.='\''.$arr[$i][$j].'';
                //echo''.$col.'</br>'.$arr[$i][$j].'';
                break;
            default:
                $col.="";
                $val.="";
        }
    }
    $col.="CollectionIndex";
    $val.='\''.$i.'\'';

    $val = preg_replace("/\s|&nbsp;/",' ',$val);
    $sql = " INSERT INTO activity (".$col.")
VALUES (".$val.") ";
    if ($conn->query($sql) === false) {
        $flag=true;
        echo "Error: INSERT INTO activity " . $sql . "<br>" . $conn->error;
    }

}

//fill period
$activityID="";
for($i=0;$i<count($arr)-1;$i++) {
    $weeks=str_replace(' ', '', $arr[$i][3]);
    $periodArr="";
    $periodArr=explode(',',$weeks);



    $sqlQ = "SELECT ActivityId FROM activity WHERE CollectionIndex='".$i."'";
    $result = $conn->query($sqlQ);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $activityID=$row["ActivityId"];
            //echo''.$activityID.'';
            //echo '</br>';
        }
    }

    for($j=0;$j<count($periodArr);$j++){

        if(strpos($periodArr[$j],'-')){
           /* echo'</br>';
            echo''.$periodArr[$j].'';
            echo'</br>';*/

            $actualDates=explode('-',$periodArr[$j]);
            $firstDate=str_replace(' ','',$actualDates[0]);
            $secondDate=str_replace(' ','',$actualDates[1]);
            $firstDate = DateTime::createFromFormat('d/m/y', $firstDate);
            $secondDate = DateTime::createFromFormat('d/m/y', $secondDate);
            //echo ''. $firstDate->format('Y-m-d').'-'.$secondDate->format('Y-m-d').'';
            //echo'</br>';
            $sql = " INSERT INTO period (StartDate,FinishDate,ActivityId)
    VALUES ('".$firstDate->format('Y-m-d')."','".$secondDate->format('Y-m-d')."','".$activityID."') ";
            if ($conn->query($sql) === false) {
                $flag=true;
                echo "Error:  INSERT INTO period(1) " . $sql . "<br>" . $conn->error;
            }

        }else{
            //echo'</br>';
            //echo'=======';
            //echo''.$periodArr[$j].', '.$activityID.'';
            //echo'</br>';

            $actualDate=str_replace(' ','',trim($periodArr[$j]));;
            $secDate = DateTime::createFromFormat('d/m/y', $actualDate);
            //echo'</br>';
            //echo ''.$actualDate->format('Y-m-d').'      '.$activityID.'';
            //echo'</br>';
            if($j==count($periodArr)-1){
                $query = " INSERT INTO period (StartDate,FinishDate,ActivityId) VALUES ('9999-01-01','".$secDate->format('Y-m-d')."',?) ";
            }else{
                $query = " INSERT INTO period (StartDate,ActivityId) VALUES ('".$secDate->format('Y-m-d')."',?) ";
            }
            $sql=mysqli_prepare($conn,$query);
            mysqli_stmt_bind_param($sql,'i',$activityID);
            if (mysqli_stmt_execute($sql) === false) {
                $flag=true;
                echo "Error: INSERT INTO period(2) " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

//StartDate Validation
/*$query = "UPDATE period SET StartDate='9999-11-31' WHERE StartDate='0'";
$StartDateSql=mysqli_prepare($conn,$query);
if (mysqli_stmt_execute($StartDateSql) === false) {
    echo "Error: UPDATE period SET StartDate " . $StartDateSql . "<br>" . $conn->error;
}*/

//fill modules
$query = "INSERT INTO modules(ModuleCode,ModuleStartDate,ModuleFinishDate)
              SELECT DISTINCT x.ModuleCode,MIN(x.StartDate),MAX(x.FinishDate)
              FROM (SELECT p.StartDate AS StartDate,p.FinishDate AS FinishDate,a.ModuleCode AS ModuleCode FROM period p JOIN activity a ON p.ActivityId=a.ActivityId) x
              GROUP BY x.ModuleCode";
$moduleSql=mysqli_prepare($conn,$query);
if (mysqli_stmt_execute($moduleSql) === false) {
    $flag=true;
    echo "Error: INSERT INTO modules" . $moduleSql . "<br>" . $conn->error;
}

($flag) ? $conn->rollback() :  $conn->commit();
    
}else{
    echo'ERROR';
}
?>
