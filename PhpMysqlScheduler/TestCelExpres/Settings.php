<?php
include "include/sessionStart.php";
include "include/loginValidation.php";
include "dbconn/conn.php";
?>
<?php
if(isset($_POST['saveBtn']) && isset($_POST['newPsw'])){
    //echo'<script>alert('.$_SESSION['username'].')</script>';
    $query = "UPDATE users SET UserPassw='".$_POST['newPsw']."' WHERE UserName='".$_SESSION['username']."'";
    $moduleSql=mysqli_prepare($conn,$query);
    if (mysqli_stmt_execute($moduleSql) === false) {
        echo "Error: INSERT INTO modules" . $moduleSql . "<br>" . $conn->error;
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
        hr {
            border-top: 1px dotted red;
        }
    </style>
</head>
<body style="background-color: black">
<form method='post' action='' enctype='multipart/form-data'>


    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <form class="col-12">
                <div class="form-group">
                    <?php
                    switch ($_SESSION['uType']){
                        case 'Student': echo'
                    <<div class="col">
                     <p>
                        <button id="pswBtn" name="pswBtn" type="button"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"  class="btn btn btn-lg btn-outline-dark">Change Password</button>
                     </p>
                     <div class="col">
                      <div class="collapse" id="collapseExample">
                            <input type="text" name="newPsw" id="newPsw" class="form-control" placeholder="New Password">
                            <button type="submit" id="saveBtn" style="margin-top: 10px" name="saveBtn" class="btn btn-primary">SAVE</button>
                        </div>
                        </div>
                        
                    </div>
                            ';
                            break;
                        case 'Supervisor': echo'
                    <div class="col">
                     <p>
                        <button id="pswBtn" name="pswBtn" type="button"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"  class="btn btn btn-lg btn-outline-dark">Change Password</button>
                     </p>
                     <div class="col">
                      <div class="collapse" id="collapseExample">
                            <input type="text" name="newPsw" id="newPsw" class="form-control" placeholder="New Password">
                            <button type="submit" id="saveBtn" style="margin-top: 10px" name="saveBtn" class="btn btn-primary">SAVE</button>
                        </div>
                        </div>
                        
                    </div>
                        ';
                            break;
                        case 'Marker': echo'
                   <div class="col">
                     <p>
                        <button id="pswBtn" name="pswBtn" type="button"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"  class="btn btn btn-lg btn-outline-dark">Change Password</button>
                     </p>
                     <div class="col">
                      <div class="collapse" id="collapseExample">
                            <input type="text" name="newPsw" id="newPsw" class="form-control" placeholder="New Password">
                            <button type="submit" id="saveBtn" style="margin-top: 10px" name="saveBtn" class="btn btn-primary">SAVE</button>
                        </div>
                        </div>
                        
                    </div>
                        ';
                            break;
                        case 'ProjectAdmin': echo '
                    <div class="col">
                     <p>
                        <button id="pswBtn" name="pswBtn" type="button"  data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"  class="btn btn btn-lg btn-outline-dark">Change Password</button>
                     </p>
                     <div class="col">
                      <div class="collapse" id="collapseExample">
                            <input type="text" name="newPsw" id="newPsw" class="form-control" placeholder="New Password">
                            <button type="submit" id="saveBtn" style="margin-top: 10px" name="saveBtn" class="btn btn-primary">SAVE</button>
                        </div>
                        </div>
                        
                    </div>';
                            break;
                    }
                    ?>

                </div>
            </form>
        </div>
    </div>
</form>
</body>
</html>
<script>
    $(document).ready(function () {
        $("#up").click( function(){
            $(location).attr('href', 'InputFiles.php');
        });
        $("#em").click( function(){
            $(location).attr('href', 'EmailInvites.php');
        });
        $("#sc").click( function(){
            $(location).attr('href', 'SelectSchedule.php');
        });
        $("#sm").click( function(){
            $(location).attr('href', 'calendar/index.php');
        });
    });
</script>
