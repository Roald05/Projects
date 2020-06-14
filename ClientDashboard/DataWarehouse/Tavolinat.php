<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/FilialeValidation.php';
?>
<?php
if(isset($_GET['statTav'])){
    if($_GET['statTav']!='HAP' AND $_GET['statTav']!='MBY'){
        //echo'<div class="container-fluid">';
        //echo'<div class="row">';
        // echo'<div class="col-sm-4 col-md-4"></div>';
        //echo'<div class="col-sm-4 col-md-4">';
        echo'<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Statusi i tavolines nuk mund te ndryshohet manualisht!</span><br><br>';
        //echo'</div>';
        //echo'</div>';
        //echo'</div>';
    }else{
        $statTav=$_GET["statTav"];
    }
}
?>
<?php
$BUSINESSID="";
if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
    $cidCheck=mysqli_prepare($conn, "SELECT b.CODE AS CODE FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.BUSINESSID=? AND b.STATUS=1 AND dv.STATUS=1 LIMIT 1");
    mysqli_stmt_bind_param($cidCheck,'s',$_SESSION['buCode']);

    if(mysqli_stmt_execute($cidCheck)){
        $rs=mysqli_stmt_get_result($cidCheck);
        while($result=mysqli_fetch_assoc($rs)){
            $BUSINESSID=$result["CODE"];
        }
    }
}
?>
<a ID="DilBtn" type="button" class="btn btn-danger btn-block btn-lg" href="Permbledhje.php"><span aria-hidden="true" class="glyphicon glyphicon-log-out"></span> KTHEHU </a>
<br><br>
<!--KOKA E TABELES-->
<?php
try{
    if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
        $sqlTavKoka=mysqli_prepare($conn, "SELECT s.SEKSIONID ,s.TAVOLINA ,s.TAVOLINA_EMERTIM,s.HAPJA As Ora,u.USERNAMED AS KAMARIERI , SUM(fd.VLEFTATVSH)
                FROM seksion s
                INNER JOIN (SELECT DISTINCT ft.USERID AS USERNAMED,sk.USERID FROM fatured ft INNER JOIN seksion sk ON ft.SEKSIONID=sk.SEKSIONID) u
                ON s.USERID=u.USERID
                INNER JOIN fatured fd
                ON s.SEKSIONID = fd.SEKSIONID
                WHERE fd.BUSINESSID=?
                AND fd.STATUS = 1
                AND s.STATUS=?
                AND fd.INSERT_DATE BETWEEN ? AND ?
                AND fd.CID=?
                AND fd.STATUS=1
                AND fd.IS_HYRJE_DALJE=0
                AND s.CID=?
                AND s.BUSINESSID=?
                GROUP BY s.SEKSIONID, s.TAVOLINA,s.HAPJA,u.USERNAMED ORDER BY KAMARIERI ASC LIMIT 30 ");

        mysqli_stmt_bind_param($sqlTavKoka,'sssssss',$BUSINESSID,$statTav,date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

    }elseif($_SESSION['uStatus']=='B'){
        $sqlTavKoka=mysqli_prepare($conn, "SELECT s.SEKSIONID ,s.TAVOLINA ,s.TAVOLINA_EMERTIM,s.HAPJA As Ora,u.USERNAMED AS KAMARIERI , SUM(fd.VLEFTATVSH)
                FROM seksion s
                INNER JOIN (SELECT DISTINCT ft.USERID AS USERNAMED,sk.USERID FROM fatured ft INNER JOIN seksion sk ON ft.SEKSIONID=sk.SEKSIONID) u
                ON s.USERID=u.USERID
                INNER JOIN fatured fd
                ON s.SEKSIONID = fd.SEKSIONID
                INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d
                ON d.BUSINESSID=fd.BUSINESSID
                WHERE fd.STATUS = 1
                AND s.STATUS=?
                AND fd.INSERT_DATE BETWEEN ? AND ?
                AND d.KUNDERID=?
                AND fd.CID=?
                AND fd.STATUS=1
                AND fd.IS_HYRJE_DALJE=0
                AND s.CID=?
                GROUP BY s.SEKSIONID, s.TAVOLINA,s.HAPJA,u.USERNAMED ORDER BY KAMARIERI ASC LIMIT 30");

        mysqli_stmt_bind_param($sqlTavKoka,'ssssss',$statTav,date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);

    }


    if(mysqli_stmt_execute($sqlTavKoka)){
        $rs=mysqli_stmt_get_result($sqlTavKoka);
        if(mysqli_num_rows($rs)>0){
            $oldUser="";
            while($result=mysqli_fetch_assoc($rs)){

                if($result["KAMARIERI"]!= $oldUser){
                    echo' <div id="tavolinabody"> ' ;
                    echo" <label class='control-label'> ".$result["KAMARIERI"]." </label><br/> ";
                }
                $tavolina=$result["TAVOLINA_EMERTIM"];
                $timestamp = strtotime($result["Ora"]);
                $ora=date("d M Y H:i:s",$timestamp);
                $kam=$result["KAMARIERI"];
                $sId=$result["SEKSIONID"];
                echo" <div id='$sId' style='background-image: url(assets/div_img5.jpg)' class='padding'> ";
                echo" <label class='control-label'>Tavolina :</label> ";
                echo" <label id='tavnr' class='control-label' style='color:red;margin-left: 5px'> ".$tavolina." </label><br /> ";
                echo' <label class="control-label" >Ora :</label> ';
                echo' <label id="ora" class="control-label" style="margin-left: 5px"> '.$ora.' </label><br /> ';
                echo' <label class="control-label">Kamarieri :</label> ';
                echo' <label id="kamarieri"  class="control-label" style="margin-left: 5px"> '.$kam.' </label><br /> ';
                echo' <table class="table table-responsive" style="border:none"> ';
                echo' <thead> ';
                echo' <tr> ';
                echo' <th> Artikulli </th> ';
                echo' <th> Sasia </th> ';
                echo' <th> Cmimi </th> ';
                echo' <th> Vlefta </th> ';
                echo' </tr> ';
                echo' </thead> ';
                echo' <tbody> ';
                //DETAJET E ARTIKUJVE TE TAVOLINES
                $sqlArtikujTav=mysqli_prepare($conn, "SELECT fdr.ARTIKULL as Artikulli, CAST(sum(fdr.SASIA) AS DECIMAL(18,0)) AS Sasia,
                        CAST(fdr.CMIMI AS DECIMAL(18,0)) AS Cmimi,
                        CAST((sum(fdr.SASIA) * fdr.CMIMI) AS DECIMAL(18,0)) AS Vlefta
                        From seksion s
                        INNER JOIN fatured fd On s.SEKSIONID = fd.SEKSIONID
                        INNER JOIN faturedrel fdr On fd.FATUREDID = fdr.FATUREDID
                        WHERE  fd.STATUS = 1 And s.STATUS = ? And fd.USERID=? AND s.SEKSIONID=? AND s.CID=? AND fdr.CID=?
                        Group BY Artikulli Order by Artikulli ASC");
                mysqli_stmt_bind_param($sqlArtikujTav,'ssiss',$statTav,$kam,$sId,$_SESSION['CID'],$_SESSION['CID']);
                if(mysqli_stmt_execute($sqlArtikujTav)){
                    $rs1=mysqli_stmt_get_result($sqlArtikujTav);
                    while($result=mysqli_fetch_assoc($rs1)){
                        $artikull=$result["Artikulli"];
                        $sasia=$result["Sasia"];
                        $cmimiArtikuj=$result["Cmimi"];
                        $vleftaArtikuj=$result["Vlefta"];
                        echo' <tr class="info"> ';
                        echo' <td id="artikulli" > '.$artikull.' </td> ';
                        echo' <td id="sasia" > '.$sasia.' </td> ';
                        echo' <td id="cmimi" > '.number_format($cmimiArtikuj,0).' LEK </td> ';
                        echo' <td id="vlera" > '.number_format($vleftaArtikuj,0).' LEK </td> ';
                        echo' </tr> ';
                    }
                }
                echo' </tbody> ';
                echo' </table> ';
                echo' <label class="control-label"> Totali : </label> ';
                //TOTALI I TAVOLINES
                $sqlTotaliTav=mysqli_prepare($conn, "SELECT CAST(SUM(x.Vlefta) AS DECIMAL(18,0)) AS TotaliTav FROM (SELECT fdr.ARTIKULL as Artikulli, CAST(sum(fdr.SASIA) AS DECIMAL(18,0)) AS Sasia,
                        CAST(fdr.CMIMI AS DECIMAL(18,0)) AS Cmimi,
                        CAST((sum(fdr.SASIA) * fdr.CMIMI) AS DECIMAL(18,0)) AS Vlefta
                        From seksion s
                        INNER JOIN fatured fd On s.SEKSIONID = fd.SEKSIONID
                        INNER JOIN faturedrel fdr On fd.FATUREDID = fdr.FATUREDID
                        WHERE fd.STATUS = 1 And s.STATUS = ? And fd.USERID=? AND s.SEKSIONID=? AND s.CID=? AND fdr.CID=?
                        Group BY Artikulli Order by Artikulli ASC) AS x");
                mysqli_stmt_bind_param($sqlTotaliTav,'ssiss',$statTav,$kam,$sId,$_SESSION['CID'],$_SESSION['CID']);
                if(mysqli_stmt_execute($sqlTotaliTav)){
                    $rs2=mysqli_stmt_get_result($sqlTotaliTav);
                    while($result=mysqli_fetch_assoc($rs2)){
                        $TotaliTav=$result["TotaliTav"];
                        echo' <label id="totali" class="control-label" style="color:red;margin-left: 5px"> ' .number_format($TotaliTav,0). ' LEK </label> ';
                    }
                }
                echo ' </div> ';
                echo' <br/> <br/> ';
                echo' </div> ';
            }
        }else{
            if(strcmp($statTav,'HAP')==0){
                echo" <h4 style='color:red'> Nuk ka asnje tavoline te hapur ! </h4> ";
            }elseif(strcmp($statTav,'MBY')==0){
                echo" <h4 style='color:red'> Nuk ka asnje tavoline te mbyllur ! </h4> ";
            }
        }
    }

}catch (Exception $e){
    echo' <td> "DETAJET E TAVOLINAVE NUK U NGARKUAN ME SUKSES" </td> ';
    echo' <td> '.$e->getMessage().' </td> ';
}
?>