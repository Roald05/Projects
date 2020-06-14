<?php
include 'SupervisorInsertion.php';
include 'MarkerInsertion.php';
include 'StudentInsertion.php';
//include 'StudentEvents.php';
header("Refresh: 2; URL=StudentEvents.php");
//include 'AutoScheduler.php';
//header("Refresh: 2; URL=calendar/index.php");

?>
<!DOCTYPE html>
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
    <h2 class="font-weight-bold">Loading Supervisors,Markers,Students</h2>

    <div class="loader"></div>
</CENTER>
</body>
</html>

