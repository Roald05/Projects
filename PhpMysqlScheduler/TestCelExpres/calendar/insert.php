
<?php
include "../dbconn/conn.php";
//insert.php

//$connect = new PDO('mysql:host=localhost;dbname=meeting_scheduler', 'root', '');

if(isset($_POST["eName"])&&isset($_POST["eDesc"])&&isset($_POST["eDate"])&&isset($_POST["supId"])&&isset($_POST["etime"])&&isset($_POST["mId"])&&isset($_POST["stId"])&&isset($_POST["eColor"]))
{
    $supervisorId=0;
    $markerId=0;
    if(isset($_SESSION['username'])){

        $sqlQ = "SELECT SupervisorId FROM supervisors WHERE SupervisorEmail='".$_SESSION['username']."'";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $supervisorId=$row['SupervisorId'];
            }
        }else{
            $supervisorId=$_POST["supId"];
        }

        $sqlQ = "SELECT MarkerId FROM marker WHERE MarkerEmail='".$_SESSION['username']."'";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $markerId=$row['MarkerId'];
            }
        }else{
            $markerId=$_POST["mId"];
        }

        $sqlQ = "SELECT MAX(SchedulerId) AS maxId FROM schedulers where Status=1";
        $result = $conn->query($sqlQ);
        if ($result->num_rows>0) {
            while($row = $result->fetch_assoc()) {
                if($row['maxId']!=null){
                    $schedulerId=$row['maxId']+1;
                }else{
                    $schedulerId=1;
                }
            }
        }else{
            $schedulerId=1;
        }
    }else{
        $supervisorId=$_POST["supId"];
        $markerId=$_POST["mId"];
        $schedulerId=1;
    }

    $datetime = $_POST["eDate"];
    $date = date('Y-m-d', strtotime($datetime));
    $time=$_POST["etime"];

    if($markerId==0){
        $query = "INSERT INTO supervisorevent (SupervisorId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId,Color) 
                    VALUES (".$supervisorId.",
                    '".$_POST["eDesc"]."',
                    '".$date."',
                    '".$time."',
                    '".date('H:i', strtotime($time)+1800)."',
                    ".$_POST["stId"]." ,
                    ".$schedulerId." ,
                    '".$_POST["eColor"]."') ";
    }else{
        $query = "INSERT INTO markerevent (MarkerId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId,Color) 
                    VALUES (".$markerId.",
                    '".$_POST["eDesc"]."',
                    '".$date."',
                    '".$time."',
                    '".date('H:i', strtotime($time)+1800)."',
                    ".$_POST["stId"].",
                    ".$schedulerId.",
                    '".$_POST["eColor"]."') ";
    }

    $sql=mysqli_prepare($conn,$query);
    if (mysqli_stmt_execute($sql) === false) {
        $flag=true;
        echo "".$sql."<br>" . $conn->error;
    }
}
?>

