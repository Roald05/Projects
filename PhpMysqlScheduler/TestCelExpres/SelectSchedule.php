<?php
include "dbconn/conn.php";
include "include/sessionStart.php";
include "include/loginValidation.php";
include "include/fileExist.php";
?>
<?php

$sqlQ = "SELECT MAX(SchedulerId) AS maxId FROM schedulers where Status=1";
$result = $conn->query($sqlQ);
if ($result->num_rows>0) {
    while($row = $result->fetch_assoc()) {
        if($row['maxId']!=null){
            $schedulerId=$row['maxId'];
        }else{
            $schedulerId=1;
        }
    }
}

/*if(isset($_POST['addBtn'])){

    $sqlQ = "SELECT MAX(SchedulerId) AS maxId FROM schedulers where Status=1";
    $result = $conn->query($sqlQ);
    if ($result->num_rows >0) {
        while($row = $result->fetch_assoc()) {
            if($row['maxId']!=null){
                $SchedulerId=$row['maxId'];
                $_SESSION['schedulerId']=$SchedulerId+1 ;

                include 'AutoScheduler.php?startDate='.$_POST['startDate'].'&finishDate='.$_POST['finishDate'].'&startTime='.$_POST['startTime'].'&meetingLength='.$_POST['interval'].'';
            }else{
                $_SESSION['schedulerId']=1;

                $_SESSION['startDate']=$_POST['startDate'] ;
                $_SESSION['finishDate']=$_POST['finishDate'] ;
                $_SESSION['startTime']=$_POST['startTime'] ;
                $_SESSION['meetingTime']=$_POST['interval'] ;
                header("Location: ImportTimetable.html");
            }
        }
    }

    if(isset($_POST['schName'])){
        $sql = " INSERT INTO schedulers (SchedulerName,Status)
    VALUES ('".$_POST['schName']."',1) ";
        if ($conn->query($sql) === false) {
            echo "Error: INSERT INTO schedulers" . $sql . "<br>" . $conn->error;
        }
    }

}*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <style>
        .btn {
            border: 2px solid black;
            background-color: white;
            border-radius: 25px;
            color: black;
            width : 200px;
            height : 200px;
            padding: 14px 25px;
            margin-left: 10px;
            margin-top: 5px;
            font-size: 17px;
        }

        .button {
            border: none;
            padding: 15px 32px;
            margin-left: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 1%;
            margin-bottom: 2%;

        }
        button:hover {
            cursor: pointer;
        }
        input:hover {
            cursor: pointer;
        }

        /* Green */
        .success {
            border-color: #4CAF50;
            color: green;
        }

        .success:hover {
            background-color: #4CAF50;
            color: white;
        }

        /* Blue */
        .info {
            border-color: #2196F3;
            color: dodgerblue;
        }

        .info:hover {
            background: #2196F3;
            color: white;
        }

        /* Orange */
        .warning {
            border-color: #ff9800;
            color: orange;
        }

        .warning:hover {
            background: #ff9800;
            color: white;
        }

        /* Red */
        .danger {
            border-color: #f44336;
            color: red;
        }

        .danger:hover {
            background: #f44336;
            color: white;
        }

        /* Gray */
        .default {
            border-color: #e7e7e7;
            color: black;
        }

        .default:hover {
            background: #7a001b;
            outline-color: #7a001b;
            color: #FFF;
        }
    </style>
</head>
<body style="background-color: white">
<form method="post" action="SelectSchedule.php">
    <div class="container h-50">
        <div class="row h-100 justify-content-rigth align-items-center">
            <form class="col-15">
                <div class="form-group">
                    <div class="col">
                        <div class="row">
                            <button type="button" id="menuBtn" name="menuBtn" class="button btn-outline-danger">MENU</button>
                        </div>
                        <div class="row" id="postResult"></div>
                        <div class="row">
                            <div class="col">
                                <p>
                                    <button id="addBtn" name="addBtn" type="button"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"  class="btn default"><span style="font-size: 50px;font-weight: normal;color: #E7E7E7">+</span></br>Add Schedule</button>
                                </p>
                                <div class="collapse" id="collapseExample">
                                    <input type="text" name="schId" id="schId" class="form-control" placeholder="Scheduler Id" value=<?php echo''.$schedulerId.'' ?> disabled>
                                    <input type="text" name="schName" style="margin-top: 10px" id="schName" class="form-control" placeholder="Scheduler Name">
                                    <input type="date" name="startDate" style="margin-top: 10px" id="startDate" class="form-control" placeholder="Scheduler Name">
                                    <input type="date" name="finishDate" style="margin-top: 10px" id="finishDate" class="form-control" placeholder="start date">
                                    <input type="time" name="startTime" style="margin-top: 10px" id="startTime" class="form-control" placeholder="start time">
                                    <input type="number" name="interval" style="margin-top: 10px" id="interval" class="form-control" placeholder="meeting length">
                                    <button type="button" id="submitBtn" style="margin-top: 10px" name="addBtn" class="button btn-primary">SUBMIT</button>
                                </div>
                            </div>
                            <div class="col">
                                <?php

                                $sqlQ = "SELECT * FROM schedulers where Status=1";
                                $result = $conn->query($sqlQ);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        //$schedulerId=$row['SchedulerId'];
                                        echo'<button type="submit" id="schedulerBtn" name="schedulerBtn" class="btn info">'.$row['SchedulerName'].'</button>';

                                    }
                                }else{
                                    echo'<button class="btn danger" type="button">No Scheduler</button>';
                                }
                                /*if(isset($_POST['schedulerBtn'])){
                                    $_SESSION['schedulerId']=$schedulerId;
                                }*/

                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</form>
</body>
</html>
<script>
    $(document).ready(function(){
        $("#menuBtn").click(function(){
            $(location).attr('href', 'MainMenu.php');
        });

        $("#submitBtn").click(function(){
            var schName=document.getElementById("schName").value;
            var schId=document.getElementById("schId").value;
            var startDate=document.getElementById("startDate").value;
            var finishDate=document.getElementById("finishDate").value;
            var startTime=document.getElementById("startTime").value;
            var interval=document.getElementById("interval").value;

            if(schName===''|| schId===''|| startDate===''|| finishDate===''|| startTime===''|| interval===''){
                alert("Please all fields are required")
            }else{
                $.ajax({
                    url: 'SchedulerTest.php',
                    type: 'POST',
                    data: {schName:schName,schId:schId,startDate:startDate,finishDate:finishDate,startTime:startTime,interval:interval},
                    success: function(data){
                        if(data ==='calendar/index.php'){
                            window.location.href = data;
                        }else{
                            $( "#postResult" ).html(data);
                            //alert(data);
                        }
                    }
                });
            }

        });

        $("#schedulerBtn").click(function(){
            var schId=document.getElementById("schedulerBtn").value;

            $.ajax({
                url: 'calendar/load.php',
                type: 'POST',
                data: {schId:schId},
                success: function(data){
                    window.location.href = 'calendar/index.php';
                }
            });
        });
    });
</script>
