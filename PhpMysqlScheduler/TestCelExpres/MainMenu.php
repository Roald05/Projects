<?php
include "dbconn/conn.php";
include "include/loginValidation.php";
include "include/logout.php";
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
<body style="background-color: white">
<form method='post' action='' enctype='multipart/form-data'>


    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <form class="col-12">
                <div class="form-group">
                    <?php
                    switch ($_SESSION['uType']){
                        case 'Student': echo'
                    <div class="row">
                        <button type="button" id="sm" class="btn btn btn-lg btn-outline-success">See meetings</button>
                    </div>
                    <div class="row">
                        <button type="submit" name="logoutBtn" id="lg" class="btn btn btn-lg btn-outline-dark">Logout</button>
                    </div>
                            ';
                            break;
                        case 'Supervisor': echo'
                    <div class="row">
                        <button type="button" id="sm" class="btn btn btn-lg btn-outline-success">See meetings</button>
                    </div>
                    <div class="row">
                        <button type="submit" name="logoutBtn" id="lg" class="btn btn btn-lg btn-outline-dark">Logout</button>
                    </div>
                        ';
                            break;
                        case 'Marker': echo'
                        
                    <div class="row">
                        <button type="button" id="sc" class="btn btn btn-lg btn-outline-primary">Scheduler</button>
                    </div>
                    <div class="row">
                        <button type="button" id="sm" class="btn btn btn-lg btn-outline-success">See meetings</button>
                    </div>
                    <div class="row">
                        <button type="submit" name="logoutBtn" id="lg" class="btn btn btn-lg btn-outline-dark">Logout</button>
                    </div>
                        ';
                            break;
                        case 'ProjectAdmin': echo '<div class="row">
                        <button type="button" id="up" class="btn btn btn-lg btn-outline-secondary">Upload Files</button>
                    </div>
                    <div class="row">
                        <button type="button" id="em"  class="btn btn btn-lg btn-outline-danger">Email to members</button>
                    </div>
                    <div class="row">
                        <button type="button" id="sc" class="btn btn btn-lg btn-outline-primary">Scheduler</button>
                    </div>
                    <div class="row">
                        <button type="button" id="sm" class="btn btn btn-lg btn-outline-success">See meetings</button>
                    </div>
                    <div class="row">
                        <button type="submit" name="logoutBtn" id="lg" class="btn btn btn-lg btn-outline-dark">Logout</button>
                    </div>';
                            break;

                    }
                    ?>
                    <div class="row">
                        <button type="button" id="st" class="btn btn btn-lg btn-dark">Settings</button>
                    </div>
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
        $("#st").click( function(){
            $(location).attr('href', 'Settings.php');
        });
    });
</script>
<?php

?>
