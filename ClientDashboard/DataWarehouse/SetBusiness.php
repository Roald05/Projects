<?php
include 'dbconn/dbconn.php';
require_once('include/SessionStart.php');
include 'include/LicenseChangeKontroll.php';
include 'include/SessionValidation.php';
include 'include/AdminValidation.php';
?>

<?php
$businessCode1="";
if(isset($_POST['buId'])){
    if($_POST['buId']!=""){
        $_SESSION['buID']=$_POST['buId'];
    }
}
$sqlBusinessName=mysqli_prepare($conn, "SELECT CODE FROM businesses WHERE BUSINESSID=? AND STATUS=1");

mysqli_stmt_bind_param($sqlBusinessName,'s',$_SESSION['buCode']);
if(mysqli_stmt_execute($sqlBusinessName)){
    $rs=mysqli_stmt_get_result($sqlBusinessName);
    while($result=mysqli_fetch_assoc($rs)){
        $bName=$result["CODE"];
    }
}
?>
<?php
if(isset($_POST['addUserBtn'])){
    if(isset($_POST['businessCode'])){
        if($_POST['businessCode']!=""){
            $_SESSION['buCode']= $_SESSION['buID'];
            $_SESSION['buDesc']=$_POST['businessDesc'];
            header('Location: setUser.php');
        }
    }
}
if(isset($_POST['addDeviceBtn'])){
    if(isset($_POST['businessCode'])){
        if($_POST['businessCode']!=""){
            $_SESSION['buCode']= $_SESSION['buID'];
            $_SESSION['buDesc']=$_POST['businessDesc'];
            header('Location: CreateDevice.php');

        }
    }
}
if(isset($_POST['addCidBtn'])){
    if(isset($_POST['businessCode'])){
        if($_POST['businessCode']!=""){
            $_SESSION['buCode']= $_SESSION['buID'];
            $_SESSION['buDesc']=$_POST['businessDesc'];
            header('Location: SetCid.php');

        }
    }
}
if(isset($_POST['displayData'])){
    if(isset($_POST['businessCode'])){
        if($_POST['businessCode']!=""){
            $_SESSION['buCode']= $_SESSION['buID'];
            $_SESSION['buDesc']=$_POST['businessDesc'];

            header('Location: Filialet.php');;
        }
    }
}
if(isset($_POST['resetEvent'])){
    $bName="";
    unset($_SESSION['buID']);
    unset($_SESSION['businessCode']);
    unset($_SESSION['bName']);
    unset($_SESSION['buDesc']);
    unset($_SESSION['buCode']);
}
?>
<html>
<head>
    <title>
        SoftExpres - CelExpres
    </title><link href="css/bootstrap.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css"/>
    <style>
        body {
            padding-top: 60px;
            background-color: #122b40;
        }
        #businessSearch{
            background-image: url('');
        }
        .table-hover tbody tr:hover td{
            background: gray ;
        }
        @media (max-width: 980px) {
            body {
                padding-top: 30px;

            }
        }
    </style>
</head>
<body >

<form name="form1" target="_blank" method="post" id="form1">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <button type="button" ID="logoutBtnAdmin" name="logoutBtnAdmin" href="index.php" class="btn btn-danger btn-block btn-group-sm" style="font-weight:bold;">Dalje <span aria-hidden="true" class="glyphicon glyphicon-arrow-left"></span></button>
            </div>
            <div class="col-sm-2">
                <input name="businessSearch" type="text" id="businessSearch" class="form-control" placeholder="Kerko sipas BUSINESS..." style="font-size:small;">
            </div>
            <div class="col-sm-2">
                <input name="usersSearch" type="text" id="usersSearch" class="form-control" placeholder="Kerko sipas USER..." style="font-size:small;">
            </div>
            <div class="col-sm-2">
                <input name="deviceSearch" type="text" id="deviceSearch" class="form-control" placeholder="Kerko sipas DEVICE..." style="font-size:small;">
            </div>
            <div class="col-sm-2">
                <input name="cidSearch" type="text" id="cidSearch" class="form-control" placeholder="Kerko sipas CID..." style="font-size:small;">
            </div>
        </div>
        <br><br>
        <div class="row" style="margin-left: auto">
            <div class="col-sm-3">
                <Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Business code :</Label>
            </div>
            <div class="col-sm-3">
                <input name="businessCode" type="text" id="businessCode" class="form-control" placeholder="BUSINESS CODE..." style="font-size:large;" value="<?php if(isset($bName)){ echo''.$bName.'';} ?>">
            </div>
            <div class="col-sm-2">
                <button type="submit" id="displayData" name="displayData" class="btn btn-primary btn-block btn-group" style="font-weight:bold;">DISPLAY DATA <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span></button>
            </div>
        </div>
        <br>
        <div class="row" style="margin-left: auto">
            <div class="col-sm-3">
                <Label ID="businessCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Business description :</Label>
            </div>
            <div class="col-sm-3">
                <textarea name="businessDesc" cols="40" rows="5" id="businessDesc" class="form-control" placeholder="BUSINESS DESCRIPTION..." style="font-size:large;"><?php if(isset($_SESSION['buDesc'])){ echo''.$_SESSION['buDesc'].'';} ?></textarea>
            </div>

        </div>
        <br><br>
        <div  class="row">
            <div class="col-sm-2">
                <button type="button" id="reset" name="reset" class="btn btn btn-block btn-group-sm" style="font-size:small;font-weight:bold;">RESET <span aria-hidden="true" class="glyphicon glyphicon-refresh"></span></button>
            </div>
            <?php
            if($_SESSION['uStatus']=='A'){
                echo'<div class="col-sm-2" style="margin-right: 10%">
                         <button type="button" id="saveBtn" name="saveBtn" class="btn btn-success btn-block btn-group-sm" style="font-size:small;font-weight:bold;">SAVE <span aria-hidden="true" class="glyphicon glyphicon-floppy-saved"></span></button>
                     </div>';
            }
            ?>
            <div class="col-sm-2">
                <button type="submit" id="addUserBtn" name="addUserBtn" class="btn btn-warning btn-block btn-group-sm" style="font-size:small;font-weight:bold;"> USERS <span aria-hidden="true" class="glyphicon glyphicon-user"></span></button>
            </div>
            <div class="col-sm-2">
                <button type="submit" id="addDeviceBtn" name="addDeviceBtn" class="btn btn-primary btn-block btn-group-sm" style="font-size:small;font-weight:bold;"> DEVICES <span aria-hidden="true" class="glyphicon glyphicon-phone"></span></button>
            </div>
            <div class="col-sm-2">
                <button type="submit" id="addCidBtn" name="addCidBtn" class="btn btn-info btn-block btn-group-sm" style="font-size:small;font-weight:bold;"> CID <span aria-hidden="true" class="glyphicon glyphicon-home"></span></button>
            </div>
        </div>

        <br><br>

    </div>
</form>
<div id="result">

</div>
</body>
</html>
<script type="text/javascript">
    var userType="<?php if(isset($_SESSION['uStatus'])){echo''.$_SESSION['uStatus'].'';} ?>";

    $(document).ready(function(){

        $(document).on('submit', '#form1', function(event){
            if(document.getElementById('businessCode').value==""){
                event.preventDefault();
                alert(" Asnje biznes i zgjedhur! ");
            }
        });

        if(userType!='A'){
            $("#businessDesc").prop("readonly",true);
            $("#businessCode").prop("readonly",true);
        }

        load_data();
        load_data_usersSearch();
        load_data_deviceSearch();
        load_data_cidSearch();

        function load_data(businessSearchChar)
        {
            $.ajax({
                url:'searchBusinesses.php',
                method:'post',
                data:{businessSearchChar:businessSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }

        function load_data_usersSearch(usersSearchChar)
        {
            $.ajax({
                url:'searchBusinesses.php',
                method:'post',
                data:{usersSearchChar:usersSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }

        function load_data_deviceSearch(deviceSearchChar)
        {
            $.ajax({
                url:'searchBusinesses.php',
                method:'post',
                data:{deviceSearchChar:deviceSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }

        function load_data_cidSearch(cidSearchChar)
        {
            $.ajax({
                url:'searchBusinesses.php',
                method:'post',
                data:{cidSearchChar:cidSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }

        $("#businessSearch").keyup(function(){
            var businessSearchChar = $(this).val();
            if(businessSearchChar != "")
            {
                load_data(businessSearchChar);
            }
            else
            {
                load_data();
            }
        });
        $("#usersSearch").keyup(function(){
            var usersSearchChar = $(this).val();
            if(usersSearchChar != "")
            {
                load_data_usersSearch(usersSearchChar);
            }
            else
            {
                load_data_usersSearch();
            }
        });
        $("#deviceSearch").keyup(function(){
            var deviceSearchChar = $(this).val();
            if(deviceSearchChar != "")
            {
                load_data_deviceSearch(deviceSearchChar);
            }
            else
            {
                load_data_deviceSearch();
            }
        });
        $("#cidSearch").keyup(function(){
            var cidSearchChar = $(this).val();
            if(cidSearchChar != "")
            {
                load_data_cidSearch(cidSearchChar);
            }
            else
            {
                load_data_cidSearch();
            }
        });

        $("#saveBtn").click(function(){
            var bCode1 = document.getElementById('businessCode').value.trim();
            var bDesc1 = document.getElementById('businessDesc').value;
            var businessID=sessionStorage.getItem("bId");
            if(businessID==null){businessID="";}
            if(bCode1 != "")
            {
                //alert(businessID);
                $.ajax({
                    url:'InsertUpdateBusiness.php',
                    method:'post',
                    data:{bCode1:bCode1,bDesc1:bDesc1,businessID:businessID},
                    async: false,
                    success: function(data)
                    {
                        if(data=='0'){
                            alert("Ky emertim ekziston ose eshte i fshire!");
                        }else{
                            $("#result").load('SearchBusinesses.php');
                            document.getElementById('businessCode').value="";
                            document.getElementById('businessDesc').value="";
                        }
                    }
                });
                sessionStorage.clear();
            }else{
                alert("Duhen plotesuar te gjitha fushat")

            }
        });

    });
</script>
<script>
    $(document).ready(function(){
        $("#logoutBtnAdmin").click(function(){
            var logoutBtnAdm=1;
            window.location.href="index.php";
            $.ajax({
                url:'index.php',
                method:'post',
                data:{logoutBtnAdm:logoutBtnAdm}
            });
            sessionStorage.clear();
        });
    });
</script>
<script>
    $(document).ready(function(){
        $("#reset").click(function(){
            document.getElementById('businessCode').value="";
            document.getElementById('businessDesc').value="";
            var resetEvent=1;
            $.ajax({
                url:'setBusiness.php',
                method:'post',
                data:{resetEvent:resetEvent}
            });
            sessionStorage.clear();
        });
    });
</script>

<script>
    function FillFunction(bCode,bDesc,buId){
        document.getElementById('businessCode').value=bCode;
        document.getElementById('businessDesc').value=bDesc;
        sessionStorage.setItem("bId",buId);
        $.ajax({
            url:'setBusiness.php',
            method:'post',
            data:{buId:buId}
        });
    }
    function openBusiness(el){
        var rowIndex=0;
        if(userType=='A'){
            rowIndex=1;
        }
     var bCode=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex].firstChild.innerHTML;
     var bDesc=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+1].firstChild.innerHTML;
     var buId=el.parentNode.id;
        FillFunction(bCode,bDesc,buId);
     }
</script>

<script>
    function deleteFunction(el1){
        var dbuID=el1.parentNode.id;
        if(dbuID != 0)
        {
            var response=confirm("Deshironi ta fshini kete rekord ?");
            if(response){
                //alert(dbuID);
                $.ajax({
                    url:'deleteBusiness.php',
                    method:'post',
                    data:{dbuID:dbuID},
                    success: function(data)
                    {
                        $("#result").html(data);
                        document.getElementById('businessCode').value="";
                        document.getElementById('businessDesc').value="";
                        sessionStorage.clear();

                    }

                });
            }
        }
    }
</script>
<script>
    $(document).on('keyup keypress', function(e){
        var keyCode = e.keyCode || e.which;
        if(keyCode === 13 && ($(event.target)[0] != $("textarea")[0])){
            e.preventDefault();
            return false;
        }
    });
</script>