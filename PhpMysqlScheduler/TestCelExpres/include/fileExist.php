<?php
$makerFileExists=false;
$supervisorFileExists=false;
$studentFileExists=false;
$timetableFileExists=false;


$makerFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/MarkerFile.csv");
$supervisorFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/SupervisorList.csv");
$studentFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/StudentFile.csv");
$timetableFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/TestCelExpres/DataFiles/WebTimetables.html");

/*$makerFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/MarkerFile1.csv");
$supervisorFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/SupervisorList.csv");
$studentFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/StudentFile.csv");
$timetableFileExists=file_exists($_SERVER['DOCUMENT_ROOT']."/websites/roald/DataFiles/WebTimetables.html");*/

if(!$makerFileExists || !$supervisorFileExists || !$studentFileExists || !$timetableFileExists){
     /*echo '<script>
          if(confirm("The files needed for this page are not uploaded do you want to upload them?")){ 
           window.location="InputFiles.php";
           }
          </script>';*/

   echo '<script>
          alert("'.$_SERVER['DOCUMENT_ROOT'].'"+"/websites/ScheduleWebsite/DataFiles/StudentFile.csv");
          </script>';

}
?>
