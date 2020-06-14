<?php include "../dbconn/conn.php"; ?>
<?php

//update.php

//$connect = new PDO('mysql:host=localhost;dbname=meeting_scheduler', 'root', '');

if(isset($_POST["id"])&&isset($_POST["start"])&&isset($_POST["end"])&&isset($_POST["desc"]))
{
    $startDate=explode(" ",$_POST["start"]);
    $startTime=$startDate[1];

    $endDate=explode(" ",$_POST["end"]);
    $endTime=$endDate[1];
    $query1='';

    $sqlQ = "SELECT SupervisorId FROM supervisorEvent WHERE SupervisorId=".$_POST["id"]." and Description='".$_POST["desc"]."'";
    $result = $conn->query($sqlQ);
    if ($result->num_rows > 0) {
        $query1 = "UPDATE supervisorevent SET EventDate=?,StartTime=?,FinishTime=? WHERE Id=? ";
    }



    $sqlQ = "SELECT MarkerId FROM markerEvent WHERE MarkerId=".$_POST["id"]." and Description='".$_POST["desc"]."'";
    $result = $conn->query($sqlQ);
    if ($result->num_rows > 0) {
        $query1 = "UPDATE markerevent SET EventDate=?,StartTime=?,FinishTime=? WHERE Id=? ";
    }

    /*if($_POST["desc"]=='Supervisor Primary Dates'){

    }
    else{
        $query1 = "UPDATE markerevent SET EventDate=?,StartTime=?,FinishTime=? WHERE Id=? ";
    }*/

    $statement = $conn->prepare($query1);
    $statement->bind_param('ssss',$startDate[0], $startTime,$endTime,$_POST["id"]);
    $statement->execute();

}

?>

