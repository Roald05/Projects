<?php
/*include "dbconn/conn.php";
include "include/sessionStart.php";

if($_GET['startDate'] && $_GET['startDate'] && $_GET['startDate'] && $_GET['startDate']){

    $startDate=date('Y-m-d',strtotime($_GET['startDate']));
    $finishDate=date('Y-m-d',strtotime($_GET['finishDate']));

    $eventLength=$_SESSION['meetingTime'];
    $startTime=date("H:i",strtotime( $_GET['startTime']));
    $finishTime=date("H:i", strtotime('+'.$eventLength.' minutes', strtotime( $_GET['startTime'])));

}else{

    $startDate=date('Y-m-d',strtotime($_SESSION['startDate']));
    $finishDate=date('Y-m-d',strtotime($_SESSION['finishDate']));

    $eventLength=$_SESSION['meetingTime'];
    $startTime=date("H:i",strtotime( $_SESSION['startTime']));
    $finishTime=date("H:i", strtotime('+'.$eventLength.' minutes', strtotime( $_SESSION['startTime'])));

}

    $studentId=1;
    $schedulerId=$_SESSION['schedulerId'];


RunScheduler($conn,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$schedulerId);
    function RunScheduler($conn,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$schedulerId){
        //$isBusy=false;
        $arr=[];
        $lastTime="";
        $sqlQ = "SELECT * FROM studentevent 
        WHERE StudentId='".$studentId."'
        AND (StartTime  BETWEEN '".$startTime."' AND '".$finishTime."'
        or FinishTime  BETWEEN '".$startTime."' AND '".$finishTime."')
        AND EventDate = '".$startDate."'
        UNION ALL
        SELECT * FROM supervisorevent
        WHERE StudentId='".$studentId."'
        AND (StartTime  BETWEEN '".$startTime."' AND '".$finishTime."'
        or FinishTime  BETWEEN '".$startTime."' AND '".$finishTime."')
        AND EventDate = '".$startDate."'
        GROUP BY EventDate
        ORDER BY FinishTime DESC LIMIT 1";
        $result = $conn->query($sqlQ);
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                //echo''.$row['FinishTime'].'';
                $lastTime=$row['FinishTime'];
            }

            if($startDate>=$finishDate){
                return $arr;
            }else{
                if($startTime<date("H:i",strtotime('21:00'))){

                    $startTime=date("H:i",strtotime($lastTime)+3600);
                    $finishTime=date("H:i", strtotime($startTime)+$eventLength*60);
                    RunScheduler($conn,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$schedulerId);
                }else{
                    $startDate=date('Y-m-d',strtotime($startDate.'+2 day'));
                    $startTime=date("H:i",strtotime('08:00'));
                    $finishTime=date("H:i", strtotime('08:00')+$eventLength*60);
                    RunScheduler($conn,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$schedulerId);
                }
            }
        }else{
            $arr[0]=$startDate;
            $arr[1]=$finishDate;
            $arr[2]=$eventLength;
            $arr[3]=$startTime;
            $arr[4]=$finishTime;
            $arr[5]=$studentId;

            $sqlSupervisor= "SELECT SupervisorId FROM students WHERE StudentId='".$studentId."'";
            $result = $conn->query($sqlSupervisor);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                        $supervisorId=$row['SupervisorId'];
                }
                $query = " INSERT INTO supervisorevent (SupervisorId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId) VALUES (?,'Supervisor Primary Dates',?,?,?,?,?) ";
                $sql=mysqli_prepare($conn,$query);
                mysqli_stmt_bind_param($sql,'ssssss',$supervisorId,$startDate,$startTime,$finishTime,$studentId,$schedulerId);
                if (mysqli_stmt_execute($sql) === false) {
                    $flag=true;
                    echo "". $sql . "<br>" . $conn->error;
                }
            }
            $sqlMarker= "SELECT MarkerId FROM students WHERE StudentId='".$studentId."'";
            $result = $conn->query($sqlMarker);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $markerId=$row['MarkerId'];
                }
                $query = " INSERT INTO markerevent (MarkerId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId) VALUES (?,'Marker Primary Dates',?,?,?,?,?) ";
                $sql=mysqli_prepare($conn,$query);
                mysqli_stmt_bind_param($sql,'ssssss',$markerId,$startDate,$startTime,$finishTime,$studentId,$schedulerId);
                if (mysqli_stmt_execute($sql) === false) {
                    $flag=true;
                    echo "". $sql . "<br>" . $conn->error;
                }
            }


            //echo '<pre>'; print_r($arr); echo '</pre>';

            if($startDate>=$finishDate){
                return $arr;
            }else{
                $startDate=date('Y-m-d',strtotime($startDate.'+2 day'));
                $startTime=date("H:i",strtotime('08:00'));
                $finishTime=date("H:i", strtotime('08:00')+$eventLength*60);
                $studentId=$studentId+1;
                RunScheduler($conn,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$schedulerId);
            }
        }
    }*/
?>
