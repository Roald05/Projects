<?php
include 'dbconn/dbconn.php';
include 'include/Test.php';
include 'include/SessionStart.php';

if(isset($_SESSION['provoPerseri'])){
    if($_SESSION['provoPerseri']>2){
        header('Location: ProvoPerseri.php');
    }
}elseif(!isset($_SESSION['provoPerseri'])){
    $_SESSION['provoPerseri']=0;
}
$sessionId=session_id();
?>

<html xmlns="http://www.w3.org/1999/xhtml"><head><title>
        Verifiko Pajisjen - CelExpres
    </title><link href="css/bootstrap.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/jquery.min.js"></script>
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
<form name="form1" method="post" action="SetCookie.php" id="form1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 col-md-4"></div>
            <div class="col-sm-4 col-md-4">
                <?php
                include 'include/DeviceValidation.php';
                ?>
                <div class="form-group">
                    <input name="pajisjaSer" type="text" id="pajisjaSer" class="form-control" placeholder="Seriali Pajisjes..." style="font-size:Large;" value="<?php echo''.substr($sessionId,-6).''; ?>">
                </div>
                <div class="form-group">
                    <select name="pajisjaID" id="pajisjaID" class="form-control" style="font-size:Large;">
                        <option selected disabled hidden value="Zgjidh Pajisjen..." >Zgjidh Pajisjen...</option>
                        <?php
                        $deviceId="SELECT DESCRIPTION,ID FROM devices WHERE STATUS=1 AND VALIDATION_DATE=0";
                        $rs=mysqli_query($conn,$deviceId) or die(mysqli_error($conn));
                        if($rs != false){
                            while($result=$rs->fetch_assoc()){
                                $device=$result["DESCRIPTION"];
                                echo'<option>'.$device.'</option>';
                            }
                        }
                        ?>
                    </select>
                    </div>
                    <div class="form-group">
                        <input name="licenceID" type="text" id="licenceID" class="form-control" placeholder="Licenca e Pajisjes..." style="font-size:Large;">
                    </div>

                <button id="verifyBtn" name="verifyBtn" type="submit" class="btn btn-warning btn-block btn-lg"> VERIFIKO <span aria-hidden="true" class="glyphicon glyphicon-refresh"></span></button>

            </div>
            <div class="col-sm-4 col-md-4"></div>
        </div>
    </div></form>
</body></html>
<script type="text/javascript">
    $(document).ready(function(){
        $("#pajisjaSer").prop("readonly",true);
    });
</script>