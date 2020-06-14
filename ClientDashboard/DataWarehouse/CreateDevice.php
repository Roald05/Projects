<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/AdminValidation.php';
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

<form name="form1" method="post"  id="form1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                <a type="button" id="back" name="back" class="btn btn-danger btn-block btn-group-sm" style="font-weight:bold;" href="SetBusiness.php">BACK <span aria-hidden="true" class="glyphicon glyphicon-arrow-left"></span></a>
            </div>
            <div class="col-sm-2">
                <input name="deviceSearch" type="text" id="deviceSearch" class="form-control" placeholder="Kerko sipas DEVICE..." style="font-size:small;">
            </div>
        </div>
        <br><br>
        <div class="row" style="margin-left: inherit;margin-top: 1%">
            <div class="col-sm-3">
                <Label ID="kunderCodeLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">KunderID :</Label>
            </div>
            <div class="col-sm-3">
                <input type="text" name="kunderCode" id="kunderCode" class="form-control" placeholder="KunderID..." style="font-size:large;"/>
            </div>
        </div>
        <div class="row" style="margin-left: inherit;margin-top: 1%">
            <div class="col-sm-3">
                <Label ID=insertDateLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Insert Date :</Label>
            </div>
            <div class="col-sm-3">
                <input name="insertDate" type="datetime-local" id="insertDate" class="form-control" placeholder="Insert Date..." style="font-size:large;">
            </div>
        </div>
        <div class="row" style="margin-left: inherit;margin-top: 1%">
            <div class="col-sm-3">
                <Label ID="validationDateLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Validation Date :</Label>
            </div>
            <div class="col-sm-3">
                <input name="validationDate" type="datetime-local" id="validationDate" class="form-control" placeholder="Validation Date..." style="font-size:large;">
            </div>
        </div>
        <div class="row" style="margin-left: inherit;margin-top: 1%">
            <div class="col-sm-3">
                <Label ID="descLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Device Description :</Label>
            </div>
            <div class="col-sm-3">
                <textarea name="descDevice" cols="40" rows="5" id="descDevice" class="form-control" placeholder="Description..." style="font-size:large;"></textarea>
            </div>
        </div>
        <div class="row" style="margin-left: inherit;margin-top: 1%">
            <div class="col-sm-3">
                <Label ID="updateDateLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Expire Date :</Label>
            </div>
            <div class="col-sm-3">
                <input name="expireDate" type="datetime-local" id="expireDate" class="form-control" placeholder="Expire Date..." style="font-size:large;">
            </div>
        </div>
        <br>
        <br><br>
        <div  class="row">
            <div class="col-sm-2">
                <button type="button" id="reset" name="reset" class="btn btn btn-block btn-group-sm" style="font-size:small;font-weight:bold;">RESET <span aria-hidden="true" class="glyphicon glyphicon-refresh"></span></button>
            </div>
            <?php
            if($_SESSION['uStatus']=='A'){
                echo'<div class="col-sm-2" style="margin-right: 10%">
                         <button type="button" onclick="saveButton()" id="saveBtn" name="saveBtn" class="btn btn-success btn-block btn-group-sm" style="font-size:small;font-weight:bold;">SAVE <span aria-hidden="true" class="glyphicon glyphicon-floppy-saved"></span></button>
                     </div>';
            }
            ?>
            <div class="col-sm-2">
                <button type="submit" id="rigjenero" name="rigjenero" class="btn btn-warning btn-block btn-group-sm" style="font-size:small;font-weight:bold;">RIGJENERO <span aria-hidden="true" class="glyphicon glyphicon-user"></span></button>
            </div>
        </div>
        <br><br>

    </div>
</form>
<div id="result">

</div>
</body>
</html>
<script>
    $('#form1').on('keyup keypress', function(e){
        var keyCode = e.keyCode || e.which;
        if(keyCode === 13 && !$(document.activeElement).is('textarea')){
            e.preventDefault();
            return false;
        }
    });
</script>
<script type="text/javascript">
    var userType="<?php if(isset($_SESSION['uStatus'])){echo''.$_SESSION['uStatus'].'';} ?>";
    $(document).ready(function(){
        sessionStorage.clear();
        if(userType!='A'){
            $("#kunderCode").prop("readonly",true);
            $("#insertDate").prop("readonly",true);
            $("#expireDate").prop("readonly",true);
            $("#descDevice").prop("readonly",true);
            $("#validationDate").prop("readonly",true);
        }else if(userType=='A'){
            $("#kunderCode").prop("readonly",true);
            $("#insertDate").prop("readonly",true);
            $("#validationDate").prop("readonly",true);
        }
        load_data_deviceSearch();

        if(userType=='A'){
            document.getElementById('kunderCode').value="<?php echo''.bin2hex(openssl_random_pseudo_bytes(8)).''; ?>";
        }else if(userType=='S'){
            document.getElementById('kunderCode').value="";
        }
        document.getElementById('insertDate').value="<?php echo''.date('Y-m-d\TH:i').''; ?>";
        document.getElementById('expireDate').value="<?php echo''.date('Y-m-d\TH:i',strtotime('+1 year')).''; ?>";


        function load_data_deviceSearch(deviceSearchChar)
        {
            $.ajax({
                url:'searchDevices.php',
                method:'post',
                data:{deviceSearchChar:deviceSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }
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
    });
</script>
<script>
    $(document).ready(function(){
        $(document).on('submit', '#form1', function(event){
            if(document.getElementById('kunderCode').value==""){
                event.preventDefault();
                alert(" Asnje biznes i zgjedhur! ");
            }
        });
    });

</script>
<script>
    function saveButton(){
        var dCode1 = document.getElementById('kunderCode').value;
        var expDate = document.getElementById('expireDate').value;
        var dDesc =document.getElementById('descDevice').value.trim();
        var valD = document.getElementById('validationDate').value;
        var DID=sessionStorage.getItem("dId");
        if(DID==null){DID="";}
        if(dCode1 != "" && dDesc != "")
        {
            //alert(businessID);
            $.ajax({
                url:'InsertUpdateDevice.php',
                method:'post',
                data:{dCode1:dCode1,expDate:expDate,dDesc:dDesc,valD:valD,DID:DID},
                success: function(data)
                {
                    if(userType=='A'){
                        document.getElementById('kunderCode').value="<?php echo''.bin2hex(openssl_random_pseudo_bytes(8)).''; ?>";
                    }else if(userType=='S'){
                        document.getElementById('kunderCode').value="";
                    }
                    document.getElementById('insertDate').value="<?php echo''.date('Y-m-d\TH:i').''; ?>";
                    document.getElementById('expireDate').value="<?php echo''.date('Y-m-d\TH:i',strtotime('+1 year')).''; ?>";
                    document.getElementById('descDevice').value="";
                    $("#result").load('SearchDevices.php');

                }
            });
            sessionStorage.clear();
        }else{
            alert("Duhen plotesuar te gjitha fushat")
        }
    }
</script>
<script>
    $(document).ready(function(){
        $("#reset").click(function(){
            document.getElementById('kunderCode').value="<?php echo''.bin2hex(openssl_random_pseudo_bytes(8)).''; ?>";
            document.getElementById('insertDate').value="<?php echo''.date('Y-m-d\TH:i').''; ?>";
            document.getElementById('validationDate').value="";
            document.getElementById('expireDate').value="<?php echo''.date('Y-m-d\TH:i',strtotime('+1 year')).''; ?>";
            document.getElementById('descDevice').value="";
            sessionStorage.clear();
        });
    });
</script>
<script>
    $(document).ready(function(){
        $("#rigjenero").on('click',function(event){
            if(sessionStorage.length!=0 && document.getElementById('kunderCode').value!=""){
                document.getElementById('kunderCode').value="<?php echo''.bin2hex(openssl_random_pseudo_bytes(8)).''; ?>";
                document.getElementById('validationDate').value=null;
                saveButton();
            }else{
                event.preventDefault();
            }
        });
    });
</script>

<script>
    function FillFunction(dCode,dIn,dValD,dExpd,dDesc,dId){
        document.getElementById('kunderCode').value=dCode;
        document.getElementById('insertDate').value=dIn.replace(" ","\T");
        document.getElementById('validationDate').value=dValD.replace(" ","\T");
        document.getElementById('expireDate').value=dExpd.replace(" ","\T");
        document.getElementById('descDevice').value=dDesc;
        sessionStorage.setItem("dId",dId);
    }
    function openDevices(el){
        var rowIndex=0;
        if(userType=='A'){
            rowIndex=1;
        }
        var dCode=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex].firstChild.innerHTML;
        var dIn=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+1].firstChild.innerHTML;
        var dValD=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+2].firstChild.innerHTML;
        var dExpd=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+3].firstChild.innerHTML;
        var dDesc=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+4].firstChild.innerHTML;
        var dId=el.parentNode.id;
        FillFunction(dCode,dIn,dValD,dExpd,dDesc,dId);
    }
</script>

<script>
    function deleteDeviceFunction(el1){
        var duID=el1.parentNode.id;
        if(duID != 0)
        {
            var response=confirm("Deshironi ta fshini kete rekord ?");
            if(response){
                //alert(dbuID);
                $.ajax({
                    url:'deleteDevice.php',
                    method:'post',
                    data:{duID:duID},
                    success: function(data)
                    {
                        //alert(duID);
                        $("#result").html(data);
                        $("#reset").click();
                        sessionStorage.clear();

                    }

                });
            }
        }
    }
</script>