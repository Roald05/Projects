<?php
include "dbconn/conn.php";
include 'RunScheduler.php';
session_start();

if(isset($_POST['schName']) && isset($_POST['schId']) && isset($_POST['startDate']) && isset($_POST['finishDate']) && isset($_POST['startTime']) && isset($_POST['interval'])){
    /*$startDate=date('Y-m-d',strtotime('2019-09-23'));
    $finishDate=date('Y-m-d',strtotime('2019-09-30'));
    $eventLength=30;
    $startTime=date("H:i",strtotime('08:00'));
    $finishTime=date("H:i", strtotime('08:00')+$eventLength*60);*/

    $startDate=$_POST['startDate'];
    $finishDate=$_POST['finishDate'];
    $eventLength=$_POST['interval'];
    $startTime=$_POST['startTime'];
    $finishTime=date("H:i", strtotime($startTime)+$eventLength*60);

    //$studentId=1;
    $schedulerId=$_POST['schId'];
    $freeTimeSlotsArr=array();
    $genes=array();
    $population=array();
    $supervisorId=-1;
    $markerId=-1;
    $populationsize=5;

    //$countStudents=0;
    //$countSupervisor=0;
    //$countMarker=0;
    $flag=false;

    $accuracy=20000;

    $i=0;

    $sIds=array();
    //$nrOfGenes=$countStudents;
    //$nrOfGenes=50;

    if($_SESSION['uType']=='Marker'){

        $sqlQ = "SELECT MarkerId FROM marker WHERE MarkerEmail='".$_SESSION['username']."'";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $markerId=$row['MarkerId'];
            }
        }


        $sqlQ = "SELECT StudentId,SupervisorId,MarkerId from students where MarkerId=".$markerId." order by StudentId ASC";
    }
    else{
        $sqlQ = "SELECT StudentId,SupervisorId,MarkerId from students where StudentId=1 order by StudentId ASC";
    }

    $result = $conn->query($sqlQ);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $supervisorId=$row['SupervisorId'];
            $markerId=$row['MarkerId'];
            $studentId=$row['StudentId'];

            $gene = returnGenes($conn,$freeTimeSlotsArr,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$supervisorId,$markerId,$schedulerId);

            //array_merge($genes,$gene);
            $genes[]=$gene;
            $sIds[]=count($gene);

            //echo '<pre>'; print_r($sIds); echo '</pre>';

            //$chromosome=generateChromosome($genes);


        }
        //echo '<pre>'; print_r($sIds); echo '</pre>';
       $population=generatePopulation(5,$genes,$sIds);
        $fitesst=getFitestIndividuals($population);

        While(getFitestIndividuals($population)[1]>$accuracy){
            $fitesst=getFitestIndividuals($population);
            $newPopulation=crossover($fitesst[0],$population);
            $population=mutation($newPopulation);
        }



        /*echo ''.$fitesst[1].' '.$fitesst[2].'';
        echo '<pre>'; print_r($population[$fitesst[0][0]]); echo '</pre>';
        echo '<pre>'; print_r($population[$fitesst[0][1]]); echo '</pre>';
        echo '<pre>'; print_r($fitesst); echo '</pre>';*/

        if($_SESSION['uType']=='Marker'){
            foreach ($population[1] as $markerevent){

                $query = " INSERT INTO markerevent (MarkerId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId,Color) VALUES (?,'Marker Primary Dates',?,?,?,?,?,'Gray') ";
                $sql=mysqli_prepare($conn,$query);
                mysqli_stmt_bind_param($sql,'ssssss',$markerevent[7],$markerevent[0],$markerevent[3],$markerevent[4],$markerevent[5],$markerevent[8]);
                if (mysqli_stmt_execute($sql) === false) {
                    $flag=true;
                    echo "". $sql . "<br>" . $conn->error;
                }

            }


            if(!$flag){
                $sql = " INSERT INTO schedulers (SchedulerName,Status)
                    VALUES ('".$_POST['schName']."',1) ";
                if ($conn->query($sql) === false) {
                    echo "Error: INSERT INTO schedulers" . $sql . "<br>" . $conn->error;
                }
                echo 'calendar/index.php';
            }

        }
        else{
            foreach ($population[0] as $supervisorevent){
                $query = " INSERT INTO supervisorevent (SupervisorId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId,Color) VALUES (?,'Supervisor Primary Dates',?,?,?,?,?,'#378006') ";
                $sql=mysqli_prepare($conn,$query);
                mysqli_stmt_bind_param($sql,'ssssss',$supervisorevent[6],$supervisorevent[0],$supervisorevent[3],$supervisorevent[4],$supervisorevent[5],$supervisorevent[8]);
                if (mysqli_stmt_execute($sql) === false) {
                    $flag=true;
                    echo "".$sql."<br>" . $conn->error;
                }
            }

            foreach ($population[1] as $markerevent){

                $query = " INSERT INTO markerevent (MarkerId,Description,EventDate,StartTime,FinishTime,StudentId,SchedulerId,Color) VALUES (?,'Marker Primary Dates',?,?,?,?,?,'Gray') ";
                $sql=mysqli_prepare($conn,$query);
                mysqli_stmt_bind_param($sql,'ssssss',$markerevent[7],$markerevent[0],$markerevent[3],$markerevent[4],$markerevent[5],$markerevent[8]);
                if (mysqli_stmt_execute($sql) === false) {
                    $flag=true;
                    echo "". $sql . "<br>" . $conn->error;
                }

            }


            if(!$flag){
                $sql = " INSERT INTO schedulers (SchedulerName,Status)
                    VALUES ('".$_POST['schName']."',1) ";
                if ($conn->query($sql) === false) {
                    echo "Error: INSERT INTO schedulers" . $sql . "<br>" . $conn->error;
                }
                echo 'calendar/index.php';
            }
        }


    }
}
?>