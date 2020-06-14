<?php include "../../dbconn/conn.php"; ?>
<?php
session_start();
//load.php

$connect = new PDO('mysql:host=localhost;dbname=meeting_scheduler', 'root', '');

$data = array();
if(isset($_POST['schId'])){
    $a=$_POST['schId'];
}else{
    $a=1;
}

if(isset($_SESSION['uType'])){

    if($_SESSION['uType']=='Supervisor'){

        $sqlQ = "SELECT SupervisorId FROM supervisors WHERE SupervisorEmail='".$_SESSION['username']."'";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $supervisorId=$row['SupervisorId'];
            }
        }

        $query = "SELECT * FROM supervisorevent join students on supervisorevent.StudentId=students.StudentId WHERE students.SupervisorId=? and supervisorevent.SchedulerId=? ORDER BY supervisorevent.Id ASC";

        $statement = $conn->prepare($query);
        $statement->bind_param('ss', $supervisorId,$a);
        $statement->execute();
        $result = $statement->get_result();
        $startDate="";
        $endDate="";

        foreach($result as $row)
        {
            $startDate.=$row["EventDate"].'T'.$row["StartTime"];
            $endDate.=$row["EventDate"].'T'.$row["FinishTime"];
            $data[] = array(
                'title' => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'description'   => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'start'   => $startDate,
                'end'   => $endDate,
                'color' => '#ffffff'
            );
            $startDate="";
            $endDate="";
        }

    }elseif($_SESSION['uType']=='Marker'){

        $sqlQ = "SELECT MarkerId FROM marker WHERE MarkerEmail='".$_SESSION['username']."'";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $markerId=$row['MarkerId'];
            }
        }

        $query1 = "SELECT * FROM markerevent join students on markerevent.StudentId=students.StudentId WHERE students.MarkerId=? and markerevent.SchedulerId=? ORDER BY markerevent.Id ASC";

        $statement = $conn->prepare($query1);
        $statement->bind_param('ss', $markerId,$a);
        $statement->execute();
        $result = $statement->get_result();
        $startDate="";
        $endDate="";

        foreach($result as $row)
        {
            $startDate.=$row["EventDate"].'T'.$row["StartTime"];
            $endDate.=$row["EventDate"].'T'.$row["FinishTime"];
            $data[] = array(
                'title' => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'description'   => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'start'   => $startDate,
                'end'   => $endDate,
                'color' => '#46b450'
            );
            $startDate="";
            $endDate="";
        }

    }elseif($_SESSION['uType']=='Student'){

        $sqlQ = "SELECT StudentId FROM students WHERE StudentEmail='".$_SESSION['username']."'";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $studentId=$row['StudentId'];
            }
        }

        $query1 = "SELECT * FROM markerevent join studentevent on markerevent.StudentId=studentevent.StudentId join supervisorevent on studentevent.StudentId=supervisorevent.StudentId WHERE studentevent.StudentId=? and studentevent.SchedulerId=? ORDER BY studentevent.Id ASC";

        $statement = $conn->prepare($query1);
        $statement->bind_param('ss', $studentId,$a);
        $statement->execute();
        $result = $statement->get_result();
        $startDate="";
        $endDate="";

        foreach($result as $row)
        {
            $startDate.=$row["EventDate"].'T'.$row["StartTime"];
            $endDate.=$row["EventDate"].'T'.$row["FinishTime"];
            $data[] = array(
                'title' => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'description'   => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'start'   => $startDate,
                'end'   => $endDate,
                'color' => '#0068e8'
            );
            $startDate="";
            $endDate="";
        }

    }else{

        $query = "SELECT * FROM supervisorevent join students on supervisorevent.StudentId=students.StudentId WHERE SchedulerId=? ORDER BY Id ASC";

        $statement = $conn->prepare($query);
        $statement->bind_param('s', $a);
        $statement->execute();
        $result = $statement->get_result();
        $startDate="";
        $endDate="";

        foreach($result as $row)
        {
            $startDate.=$row["EventDate"].'T'.$row["StartTime"];
            $endDate.=$row["EventDate"].'T'.$row["FinishTime"];
            $data[] = array(
                'title' => $row["StudentName"].' '.$row["StudentSurname"].' '.$row["Description"],
                'description'   => $row["StudentName"].' '.$row["StudentSurname"].' '.$row["Description"],
                'start'   => $startDate,
                'end'   => $endDate,
                'color' => '#ffffff'

            );
            $startDate="";
            $endDate="";
        }

        $query = "SELECT * FROM markerevent join students on markerevent.StudentId=students.StudentId WHERE SchedulerId=? ORDER BY Id ASC";

        $statement = $conn->prepare($query);
        $statement->bind_param('s', $a);
        $statement->execute();
        $result = $statement->get_result();
        $startDate="";
        $endDate="";

        foreach($result as $row)
        {
            $startDate.=$row["EventDate"].'T'.$row["StartTime"];
            $endDate.=$row["EventDate"].'T'.$row["FinishTime"];
            $data[] = array(
                'title' => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'description'   => $row["StudentName"].' '.$row["StudentSurname"].'  ,  '.$row["Description"],
                'start'   => $startDate,
                'end'   => $endDate,
                'color' => '#46b450'
            );
            $startDate="";
            $endDate="";
        }
    }
}



echo json_encode($data);

?>
