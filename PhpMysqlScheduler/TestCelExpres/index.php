
<?php
include 'include/sessionStart.php';
?>
<html>
<head>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="jquery/jquery-3.2.1.min.js"></script>
    <meta name="viewport" content="width=device-width">
    <style>
        body {
            font-family: "Lato", sans-serif;
        }



        .main-head{
            height: 150px;
            background: #FFF;

        }

        .sidenav {
            height: 100%;
            background-color: #000;
            overflow-x: hidden;
            padding-top: 20px;
        }


        .main {
            padding: 0px 10px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {padding-top: 15px;}
        }

        @media screen and (max-width: 450px) {
            .login-form{
                margin-top: 10%;
            }

            .register-form{
                margin-top: 10%;
            }
        }

        @media screen and (min-width: 768px){
            .main{
                margin-left: 40%;
            }

            .sidenav{
                width: 40%;
                position: fixed;
                z-index: 1;
                top: 0;
                left: 0;
            }

            .login-form{
                margin-top: 80%;
            }

            .register-form{
                margin-top: 20%;
            }
        }


        .login-main-text{
            margin-top: 20%;
            padding: 60px;
            color: #fff;
        }

        .login-main-text h2{
            font-weight: 300;
        }

        .btn-black{
            background-color: #000 !important;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="sidenav">
    <div class="login-main-text">
        <h2>Member<br> Login Page</h2>
        <p>Login from here to access the scheduler.</p>
    </div>
</div>
<div class="main">
    <div class="col-md-6 col-sm-12">
        <div class="login-form">
            <form method="post" action="index.php">
                <div class="form-group">
                    <?php include "dbconn/conn.php"; ?>
                    <?php
                    if(isset($_POST['username']) && isset($_POST['password'])){
                        $sqlQ = "SELECT * FROM users WHERE UserName='".$_POST['username']."' AND UserPassw='".$_POST['password']."' AND UserStatus=1";
                        $result = $conn->query($sqlQ);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                switch ($row['ActualUser']){
                                    case 'Student': $_SESSION['uType']='Student';
                                        break;
                                    case 'Supervisor': $_SESSION['uType']='Supervisor';
                                        break;
                                    case 'Marker': $_SESSION['uType']='Marker';
                                        break;
                                    case 'ProjectAdmin': $_SESSION['uType']='ProjectAdmin';
                                        break;
                                }

                            }
                            $_SESSION['username']=$_POST['username'];
                            $_SESSION['password']=$_POST['password'];

                            header("Location: MainMenu.php");

                        }else{
                            echo' <label style="color: darkred">Wrong Username or Password</label>';
                        }
                    }
                    ?>
                    <input type="text" id="username" name="username" class="form-control" placeholder="User Name">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="password" name="password"  class="form-control" placeholder="Password">
                </div>
                <button id="loginBtn" type="submit" name="loginBtn" class="btn btn-black">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>


<!------ Include the above in your HEAD tag ---------->


