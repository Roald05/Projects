<?php
if(isset($_POST['logoutBtnAdm'])) {
    session_start();
    unset($_SESSION['buID']);
    unset($_SESSION['businessCode']);
    unset($_SESSION['uStatus']);
    unset($_SESSION['buCode']);
    unset($_SESSION['buDesc']);
    unset($_SESSION['perdoruesi']);
    unset($_SESSION['fjalekalimi']);
    unset($_SESSION['CID']);
    unset($_SESSION['date1']);
    unset($_SESSION['date2']);
    session_destroy();
}
?>
<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/LicenseChangeKontroll.php';
include 'ExpKontroll.php';

function isMobileDevice(){
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",$_SERVER["HTTP_USER_AGENT"]);
}
?>

<html xmlns="http://www.w3.org/1999/xhtml"><head><title>
        Mir&eumlsevini n&euml CelExpres

    </title><link href="css/bootstrap.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            padding-top: 60px;
        }
        @media (max-width: 980px) {
            body {
                padding-top: 30px;
            }
        }
    </style>
</head>
<body background="assets/back_img.jpg">
<form name="form1" method="post" id="form1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 col-md-4"></div>
            <div class="col-sm-4 col-md-4">
                <?php
                $pajisjaPershkrim=mysqli_prepare($conn, "SELECT DESCRIPTION FROM DEVICES where KUNDERID=? AND STATUS=1");
                mysqli_stmt_bind_param($pajisjaPershkrim,'s',$_COOKIE['KUNDERID']);
                if(mysqli_stmt_execute($pajisjaPershkrim)){
                    $rs=mysqli_stmt_get_result($pajisjaPershkrim);
                    if (mysqli_num_rows($rs) > 0){
                        while($result=mysqli_fetch_assoc($rs)){

                            if(isMobileDevice()){
                                echo '<span id="Label1" class="control-label" style="color:#4fa5a4;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-phone"></span> ' .$result['DESCRIPTION'].' </span><br><br>';
                            }else{
                                echo '<span id="Label1" class="control-label" style="color:#4fa5a4;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-hdd"></span> ' .$result['DESCRIPTION'].' </span><br><br>';
                            }
                        }
                    }
                }
                ?>
                <?php include 'include/LoginValidation.php'; ?>

                <span id="Label1" class="control-label" style="font-size:Large;font-weight:bold;"></span>
                <div class="form-group">
                    <label class="control-label" for="usernameDDown"><font size="5">P&eumlrdoruesi</font></label>
                    <select name="usernameDDown" id="usernameDDown" class="form-control" style="font-size:Large;">
                        <option value="Zgjidh..." >Zgjidh...</option>
                        <?php
                        $userName="SELECT USERNAMED FROM users u INNER JOIN devices d ON u.BUSINESSID=d.BUSINESSID WHERE u.STATUS=1 AND d.STATUS=1 AND d.KUNDERID='".$_COOKIE["KUNDERID"]."' ";
                        $rs=mysqli_query($conn,$userName) or die(mysqli_error($conn));
                        if($rs != false){
                            while($result=$rs->fetch_assoc()){
                                $userId=$result["USERNAMED"];
                                if(isset($_SESSION['userId'])){
                                    if($userId==$_SESSION['userId']){
                                        echo'<option selected>'.$userId.'</option>';
                                        unset($_SESSION['userId']);
                                    }else{
                                        echo'<option>'.$userId.'</option>';
                                    }
                                }else{
                                    echo'<option>'.$userId.'</option>';
                                }
                            }
                        }
                        ?>

                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label" for="passwordTxt"><font size="5">Fjal&eumlkalimi</font></label>
                    <input name="passwordTxt" type="password" id="passwordTxt" class="form-control" placeholder="fjalekalimi..." style="font-size:Large;">
                </div>
                <button type="submit" id="loginBtn" name="loginBtn" class="btn btn-success btn-block btn-lg"> HYRJE <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span></button>

            </div>
            <div class="col-sm-4 col-md-4"></div>
        </div>
    </div></form>
</body>
</html>