<?php

$makerFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/MarkerFile.csv");
$supervisorFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/SupervisorList.csv");
$studentFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/StudentFile.csv");
$timetableFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/WebTimetables.html");

/*$makerFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/MarkerFile.csv");
$supervisorFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/SupervisorList.csv");
$studentFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/StudentFile.csv");
$timetableFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/WebTimetables.html");*/

if($makerFileExists || $supervisorFileExists || $studentFileExists || $timetableFileExists){
    echo '<script>
            if(!confirm("Files are already uploaded")){ 
           window.location="MainMenu.php";
           }else{
               //window.location="MainMenu.php"; 
           }
          </script>';
    //header("Location: MainMenu.php");
}
include "dbconn/conn.php";
include "include/sessionStart.php";
include "include/loginValidation.php";
?>
<?php
if(isset($_POST['submit'])){

    // Count total files
    $countfiles = count($_FILES['file']['name']);

    // Looping all files
    for($i=0;$i<$countfiles;$i++){
        $filename = $_FILES['file']['name'][$i];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = array('csv','html','xls','xlsx');
        if( !in_array( $ext, $allowed ) )
        {
            echo '<script>alert("Files allowed are csv,html,xls")</script>';
        }else{
            move_uploaded_file($_FILES['file']['tmp_name'][$i],'DataFiles/'.$filename);
        }
    }
    //header("Location: ImportTimetable.html");

}
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap-filestyle.min.js"> </script>
    <style>
        html,
        body {
            height: 100%;
        }
        button:hover {
            cursor: pointer;
        }
        input:hover {
            cursor: pointer;
        }

        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

</head>
<body style="background-color: black">
<form method='post' action='' enctype='multipart/form-data'>
    <div class="col">
        <button type="button" id="menuBtn" name="menuBtn" style="margin-top: 1%" class="btn btn-outline-danger">MENU</button>
    </div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <form class="col-15">
                <div class="form-group">
                    <div class="col">
                        <h2 class="font-weight-bold" style="color: white">Select multiple files at once
                            (<br>SupervisorList.csv,</br>StudentFile.csv,<br>MarkerFile.csv,<br>WebTimetables.html) </h2>
                        <input type="file" style="width: 50px" name="file[]" class="filestyle"  id="file" multiple>

                        <input type='submit' name='submit' style="margin-top: 2%" class="btn btn-outline-primary" value='Upload'>
                        <input type='button' name='load' id="load" style="margin-top: 2%" class="btn btn-outline-success" value='Load'>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div  id="content2"></div>
    <div id="info"></div>
</form>

</body>
</html>


<script>
    $(document).ready(function(){
        $("#menuBtn").click(function(){
            $(location).attr('href', 'MainMenu.php');
        });
        $("#load").click(function(){
            $(location).attr('href', 'ImportTimetable.html');
        });
    });
</script>


