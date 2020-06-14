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
                <input name="cidSearch" type="text" id="cidSearch" class="form-control" placeholder="Kerko sipas CID..." style="font-size:small;">
            </div>
        </div>
        <br><br>
        <div class="row" style="margin-left: inherit">
            <div class="col-sm-3">
                <Label ID="cidLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">CID :</Label>
            </div>
            <div class="col-sm-3">
                <input name="cidInput" type="text" id="cidInput" class="form-control" placeholder="CID..." style="font-size:large;">
            </div>
        </div>
        <br>
        <div class="row" style="margin-left: inherit">
            <div class="col-sm-3">
                <Label ID="syncLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Allow Sync :</Label>
            </div>
            <div class="col-sm-1">
                <input name="syncInput" type="checkbox" id="syncInput" class="form-control" placeholder="ALLOW SYNC..." style="padding: 20%;">
            </div>
        </div>
        <br>
        <div class="row" style="margin-left: inherit">
            <div class="col-sm-3">
                <Label ID="cidLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Start Date :</Label>
            </div>
            <div class="col-sm-3">
                <input name="startDateInput" type="date" id="startDateInput" class="form-control" placeholder="START DATE..." style="font-size:large;">
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
                         <button type="button" id="saveBtn" name="saveBtn" class="btn btn-success btn-block btn-group-sm" style="font-size:small;font-weight:bold;">SAVE <span aria-hidden="true" class="glyphicon glyphicon-floppy-saved"></span></button>
                     </div>';
            }
            ?>
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
        if(userType!='A'){
            $("#cidInput").prop("readonly",true);
            $("#syncInput").prop("disabled",true);
            $("#startDateInput").prop("readonly",true);
        }
        load_data_cidSearch();
        document.getElementById('startDateInput').value="<?php echo''.date('Y-m-d').''; ?>";
         function load_data_cidSearch(cidSearchChar)
        {
            $.ajax({
                url:'searchCids.php',
                method:'post',
                data:{cidSearchChar:cidSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }

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
            var cCode1 = document.getElementById('cidInput').value;
            var cSync =+ document.getElementById('syncInput').checked;
            var cStartD = document.getElementById('startDateInput').value;
            var cID=sessionStorage.getItem("cID");
            if(cID==null){cID="";}
            if(cCode1 != "")
            {
                //alert(businessID);
                $.ajax({
                    url:'InsertUpdateCid.php',
                    method:'post',
                    data:{cCode1:cCode1,cID:cID,cSync:cSync,cStartD:cStartD},
                    async: false,
                    success: function(data)
                    {
                        if(data=='0'){
                            alert("Ky emertim ekziston ose eshte i fshire!");
                        }else{
                            $("#result").load('SearchCids.php');
                            $("#reset").click();
                        }
                    }
                });
                sessionStorage.clear();
            }else{
                alert("Cid duhet plotesusar");
            }
        });

    });
</script>
<script>
    $(document).ready(function(){
        $("#reset").click(function(){
            document.getElementById('cidInput').value="";
            document.getElementById('syncInput').checked=false;
            document.getElementById('startDateInput').value="<?php echo''.date('Y-m-d').''; ?>";
            sessionStorage.clear();
        });
    });
</script>
<script>
    function FillFunction(cCode1,cSync,cStartD,cID){
        document.getElementById('cidInput').value=cCode1;
        document.getElementById('syncInput').checked=Boolean(Number(cSync));
        document.getElementById('startDateInput').value=cStartD;
        sessionStorage.setItem("cID",cID);
    }
    function openCID(el){
        var rowIndex=0;
        if(userType=='A'){
            rowIndex=1;
        }
        var cCode1=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex].firstChild.innerHTML;
        var cSync=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+1].firstChild.innerHTML;
        var cStartD=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+2].firstChild.innerHTML;
        var cID=el.parentNode.id;
        FillFunction(cCode1,cSync,cStartD,cID);
    }
</script>
<script>
    function deleteCidFunction(el1){
        var dcID=el1.parentNode.id;
        if(dcID != 0)
        {
            var response=confirm("Deshironi ta fshini kete rekord ?");
            if(response){
                $.ajax({
                    url:'deleteCid.php',
                    method:'post',
                    data:{dcID:dcID},
                    success: function(data)
                    {
                        $("#result").html(data);
                        document.getElementById('cidInput').value="";
                        document.getElementById('startDateInput').value="<?php echo''.date('Y-m-d').''; ?>";

                        sessionStorage.clear();

                    }
                });
            }
        }

    }
</script>
<script>
    $('#form1').on('keyup keypress', function(e){
        var keyCode = e.keyCode || e.which;
        if(keyCode === 13 && !$(document.activeElement).is('textarea')){
            e.preventDefault();
            return false;
        }
    });
</script>