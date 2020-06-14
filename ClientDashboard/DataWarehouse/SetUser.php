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
                <input name="usersSearch" type="text" id="usersSearch" class="form-control" placeholder="Kerko sipas USER..." style="font-size:small;">
            </div>
        </div>
        <br><br>
        <div class="row" style="margin-left: inherit">
            <div class="col-sm-3">
                <Label ID="cidLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Username :</Label>
            </div>
            <div class="col-sm-3">
                <input name="usernameInput" type="text" id="usernameInput" class="form-control" placeholder="USERNAME..." style="font-size:large;">
            </div>
        </div>
        <br>
        <div class="row" style="margin-left: inherit">
            <div class="col-sm-3">
                <Label ID="passwordLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">Password :</Label>
            </div>
            <div class="col-sm-3">
                <?php
                if($_SESSION['uStatus']=='A'){
                    echo '<input type="text"  name="passwordInput" cols="40" rows="5" id="passwordInput" class="form-control" placeholder="PASSWORD..." style="font-size:large"/>';
                }elseif($_SESSION['uStatus']=='S'){
                    echo '<input type="password"  name="passwordInput" cols="40" rows="5" id="passwordInput" class="form-control" placeholder="PASSWORD..." style="font-size:large"/>';
                }
                ?>
            </div>

        </div>
        <br>
        <div class="row" style="margin-left: inherit">
            <div class="col-sm-3">
                <Label ID="tipiLabel" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;color: white">TIPI :</Label>
            </div>
            <div class="col-sm-3">
                <select name="tipiInput" id="tipiInput" class="form-control" style="font-size:medium;">
                    <option value="" disabled selected hidden >Zgjidh Tipin...</option>
                    <option>BUSINESS</option>
                    <option>SYSTEM</option>
                    <option>ADMIN</option>

                </select>
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
            $("#usernameInput").prop("readonly",true);
            $("#passwordInput").prop("readonly",true);
            $("#tipiInput").prop("disabled",true);
        }
        load_data_usersSearch();

        function load_data_usersSearch(usersSearchChar)
        {
            $.ajax({
                url:'searchUsers.php',
                method:'post',
                data:{usersSearchChar:usersSearchChar},
                success: function(data)
                {
                    $("#result").html(data);
                }
            });
        }
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
        $("#saveBtn").click(function(){
            var uCode1 = document.getElementById('usernameInput').value.trim();
            var uPasw = document.getElementById('passwordInput').value.trim();
            var userTipi= document.getElementById('tipiInput').value;
            var uTipi="";
            switch(userTipi){
                case 'BUSINESS': uTipi='B';
                    break;
                case 'SYSTEM': uTipi='S';
                    break;
                case 'ADMIN': uTipi='A';
                    break;
            }
            var UID=sessionStorage.getItem("uId");
            if(UID==null){UID="";}
            if(uCode1 != "" && uPasw != "" && uTipi != "")
            {
                //alert(businessID);
                $.ajax({
                 url:'InsertUpdateUser.php',
                 method:'post',
                 data:{uCode1:uCode1,uPasw:uPasw,uTipi:uTipi,UID:UID},
                    async: false,
                    success: function(data)
                    {
                        if(data=='0'){
                            alert("Ky emertim ekziston ose eshte i fshire!");
                        }else{
                            $("#result").load('SearchUsers.php');
                            document.getElementById('usernameInput').value="";
                            document.getElementById('passwordInput').value="";
                            document.getElementById('tipiInput').value="";
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
    function FillFunction(uCode1,uPasw,uTipi,uId){
        document.getElementById('usernameInput').value=uCode1;
        document.getElementById('passwordInput').value=uPasw;
        document.getElementById('tipiInput').value=uTipi;
        sessionStorage.setItem("uId",uId);
    }
    function openUser(el){
        var rowIndex=0;
        if(userType=='A'){
            rowIndex=1;
        }
        var uCode1=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex].firstChild.innerHTML;
        var uPasw=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+1].firstChild.innerHTML;
        var uTipi=document.getElementById('businessesTable').rows[el.parentNode.parentNode.rowIndex].cells[rowIndex+2].firstChild.innerHTML;
        var uId=el.parentNode.id;
        FillFunction(uCode1,uPasw,uTipi,uId);
    }
</script>
<script>
    $(document).ready(function(){
        $("#reset").click(function(){
            document.getElementById('usernameInput').value="";
            document.getElementById('passwordInput').value="";
            document.getElementById('tipiInput').value="";
            sessionStorage.clear();
        });
    });
</script>
<script>
    function deleteUserFunction(el1){
        var duID=el1.parentNode.id;
        if(duID != 0)
        {
            var response=confirm("Deshironi ta fshini kete rekord ?");
            if(response){

                $.ajax({
                    url:'deleteUser.php',
                    method:'post',
                    data:{duID:duID},
                    success: function(data)
                    {
                        $("#result").html(data);
                        document.getElementById('usernameInput').value="";
                        document.getElementById('passwordInput').value="";
                        document.getElementById('tipiInput').value="";

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