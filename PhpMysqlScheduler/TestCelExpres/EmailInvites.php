<?php include "dbconn/conn.php"; ?>
<?php
include "dbconn/conn.php";
include "include/sessionStart.php";
include "include/loginValidation.php";
include "include/fileExist.php";
?>
<?php
set_time_limit(300);
If(isset($_POST['rr'])) {
    if($_POST["subject"]!=""){
        $subject = "".$_POST["subject"]."";
        $headers = "From: rcela05@gmail.com";

        $sqlQ = "SELECT StudentName,StudentSurname,StudentEmail FROM students";
        $result = $conn->query($sqlQ);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $to = ''.$row["StudentEmail"].'';
                $message = "Username : " . "" . $row["StudentEmail"] . "" . "\n" . "Password: " . "" . $row["StudentName"] . "" . $_POST["addMsg"] . "";
                mail($to, $subject, $message, $headers);
            }
        }

        $queryStudent = "INSERT INTO users(UserName,UserPassw,UserStatus,ActualUser)
                  SELECT StudentEmail,StudentName,1,'Student' FROM students ORDER BY StudentName ASC";
        $querySupervisor = "INSERT INTO users(UserName,UserPassw,UserStatus,ActualUser)
                     SELECT SupervisorEmail,SupervisorName,1,'Supervisor' FROM supervisors ORDER BY SupervisorName ASC";
        $queryMarker = "INSERT INTO users(UserName,UserPassw,UserStatus,ActualUser)
                     SELECT MarkerEmail,MarkerName,1,'Marker' FROM marker ORDER BY MarkerName ASC";
        $moduleSql1 = mysqli_prepare($conn, $queryStudent);
        if (mysqli_stmt_execute($moduleSql1) === false) {
            echo "Error: INSERT INTO users" . $moduleSql1 . "<br>" . $conn->error;
        }
        $moduleSql2 = mysqli_prepare($conn, $querySupervisor);
        if (mysqli_stmt_execute($moduleSql2) === false) {
            echo "Error: INSERT INTO users" . $moduleSql2 . "<br>" . $conn->error;
        }
        $moduleSql3 = mysqli_prepare($conn, $queryMarker);
        if (mysqli_stmt_execute($moduleSql3) === false) {
            echo "Error: INSERT INTO users" . $moduleSql3 . "<br>" . $conn->error;
        }
    }else{
        echo'<script>alert("Subject should be inserted")</script>';
    }

}

?>
<html>
<head>

    <meta charset="utf-8">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="bootstrap/js/bootstrap-filestyle.min.js"> </script>
    <style>
        .btn {
            cursor: pointer;
            margin-bottom: 10%;
            width: 300px;
        }
        .row{
            margin-bottom: 20px;
        }
        .form-check{
            margin-left: 25px;
        }
        hr{
            border-top: 1px dotted red;
        }
        #title{
            color: white;
        }
    </style>
</head>
<body style="background-color: black">
<form method='post' action='' enctype='multipart/form-data'>
    <div class="row">
        <button type="button" id="menuBtn" name="menuBtn" style="margin: 1%;width: 80px" class="btn btn-outline-danger fixed-top">MENU</button>
    </div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <form class="col-15">
                <div class="form-group">

                    <div class="row">
                        <h2 type="text" id="title" class="text-center">Send Email</h2>
                    </div>
                    <div class="row">
                        <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject">
                    </div>
                    <div class="row">
                        <textarea type="text" cols="10" rows="10" name="addMsg" id="addMsg" class="form-control" placeholder="Additional message"></textarea>
                    </div>
                    <div class="row">
                        <button type="submit" name="rr" id="rr" class="btn btn-lg btn-outline-warning">Send Credentials to Users</button>
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
        $("#myButton").click(function(){
            $(this).button('toggle');
        });
        $("#menuBtn").click(function(){
            $(location).attr('href', 'MainMenu.php');
        });
    });
</script>