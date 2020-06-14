<?php include "dbconn/conn.php"; ?>
<?php
//include "include/sessionStart.php";
$periodId=array();
$startDate=array();
$finishDate=array();
$sqlQ = "SELECT `PeriodId`, `StartDate`, `FinishDate` FROM `period`";
$result = mysqli_query ($conn, $sqlQ);
if ($result->num_rows > 0) {
    while($row = mysqli_fetch_array ($result)) {
        $periodId[]=$row["PeriodId"];
        $startDate[]=$row["StartDate"];
        $finishDate[]=$row["FinishDate"];
    }
}

for($i=0;$i<count($periodId);$i++){
        if($startDate[$i]=='9999-01-01'){
            
            $query1 = " INSERT INTO `dates`( `Date`, `PeriodId`, `SchedulerId`) VALUES (?,?,1) ";
            $sql1=mysqli_prepare($conn,$query1);
                mysqli_stmt_bind_param($sql1,'si',$startDate[$i],$periodId[$i]);
                if (mysqli_stmt_execute($sql1) === false) {
                    echo "Error: INSERT INTO period(2) " . $sql1 . "<br>" . $conn->error;
                }
                
        }elseif($finishDate[$i]=='0000-00-00'){
            
            $query2 = " INSERT INTO `dates`( `Date`, `PeriodId`, `SchedulerId`) VALUES (?,?,1) ";
            $sql2=mysqli_prepare($conn,$query2);
                mysqli_stmt_bind_param($sql2,'si',$finishDate[$i],$periodId[$i]);
                if (mysqli_stmt_execute($sql2) === false) {
                    echo "Error: INSERT INTO period(2) " . $sql2 . "<br>" . $conn->error;
                }
                
        }else{
            $firstDate = DateTime::createFromFormat('Y-m-d', $startDate[$i]);
            $secondDate = DateTime::createFromFormat('Y-m-d', $finishDate[$i]);
            
            for($j = $firstDate; $j <= $secondDate; $j->modify('+1 day')){
                $date=$j->format('Y-m-d');

                $query3 = " INSERT INTO `dates`( `Date`, `PeriodId`, `SchedulerId`) VALUES (?,?,1) ";
                $sql3=mysqli_prepare($conn,$query3);
                mysqli_stmt_bind_param($sql3,'si',$date,$periodId[$i]);
                if (mysqli_stmt_execute($sql3) === false) {
                    echo "Error: INSERT INTO period(2) " . $sql3 . "<br>" . $conn->error;
                }
            }
        }
}
        

$query = "INSERT INTO studentevent(StudentId,Description,EventDate,StartTime,FinishTime,SchedulerId)
              SELECT s.StudentId,a.ActivityName,d.Date,a.StartTime,a.FinishTime,d.SchedulerId 
              FROM studentsmodule s 
              INNER JOIN modules m ON s.ModuleCode=m.ModuleCode
              INNER JOIN activity a ON m.ModuleCode=a.ModuleCode 
              INNER JOIN period p ON a.ActivityId=p.ActivityId 
              INNER JOIN dates d ON p.PeriodId=d.PeriodId
              WHERE d.Date NOT IN ('9999-01-01')
              ORDER BY d.Date ASC";
$moduleSql=mysqli_prepare($conn,$query);
if (mysqli_stmt_execute($moduleSql) === false) {
    $flag=true;
    echo "Error: INSERT INTO modules" . $moduleSql . "<br>" . $conn->error;
}
header("Refresh: 2; URL=MainMenu.php");


?>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite; /* Safari */
            animation: spin 2s linear infinite;
        }

        h2{
            font-family: "Lato", sans-serif;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<CENTER>
    <h2 class="font-weight-bold">Loading Timetable</h2>

    <div class="loader"></div>
</CENTER>
</body>
</html>
