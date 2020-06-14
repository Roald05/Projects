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
<body style="background-color: black">
<form method='post' action='' enctype='multipart/form-data'>


    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <form class="col-15">
                <div class="form-group">
                    <div class="col">
                        <input style="margin-bottom: 20px;margin-top: 20px" onkeyup="search()" type="text" name="subject" id="subject" class="form-control" placeholder="Search by email">
                    </div>

                    <dic class="table-responsive">
                        <table class="table" style="color: white">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col">Password</th>
                                <th scope="col">Actual User</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sqlQ = "SELECT * FROM users WHERE UserStatus=1";
                            $result = $conn->query($sqlQ);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo'<tr>
                                    <td>'.$row['UserName'].'</td>
                                    <td>'.$row['UserPassw'].'</td>
                                    <td>'.$row['ActualUser'].'</td>
                                    <td><button class="btn btn-succes" name="idBtn" id='.$row['ActualUser'].'>Regenerate Password</button></td>
                                    <td><button class="btn btn-danger" name="deBtn" id='.$row['ActualUser'].'>Delete</button></td>
                                   </tr>';
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </dic>



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
    });
</script>
