<?php
include 'dbconn/dbconn.php';
if(!isset($_COOKIE['KUNDERID'])){
$validation=false;
    if(isset($_POST['verifyBtn'])){

        if(isset($_POST['pajisjaID'])){
            try{
                if(isset($_POST['pajisjaSer']) && isset($_POST['pajisjaID']) && isset($_POST['licenceID'])){

                    $encrypted=$_POST['licenceID'];
                    if(substr($encrypted,-1)!= '='){
                        $encrypted='roald';
                    }
                    $dec=dec($encrypted);
                    $ser=substr($sessionId,-6);

                    if(strcasecmp($dec,$ser) !== 0){
                        $validation=false;
                    }else{
                        $validation=true;
                    }

                    if($validation===true){

                        $sqlVerifikimId=mysqli_prepare($conn, "SELECT KUNDERID FROM devices WHERE DESCRIPTION=? AND STATUS=1");

                        mysqli_stmt_bind_param($sqlVerifikimId,'s',$_POST['pajisjaID']);

                        if(mysqli_stmt_execute($sqlVerifikimId)){
                            $rs=mysqli_stmt_get_result($sqlVerifikimId);
                            if(mysqli_num_rows($rs)>0){
                                while($result2=mysqli_fetch_assoc($rs)){
                                    $kunderId=$result2["KUNDERID"];
                                    setcookie("KUNDERID",$kunderId,time()+(10*365*24*60*60));

                                    $sqlValidation=mysqli_prepare($conn, "UPDATE devices SET VALIDATION_DATE=? WHERE KUNDERID=? AND STATUS=1");
                                    mysqli_stmt_bind_param($sqlValidation,'ss',date('Y-m-d H:i'),$kunderId);
                                    mysqli_stmt_execute($sqlValidation);
                                    mysqli_stmt_close($sqlValidation);

                                    $sqlSetExp=mysqli_prepare($conn, "UPDATE devices SET EXPIRE_DATE=? WHERE KUNDERID=? AND STATUS=1");
                                    mysqli_stmt_bind_param($sqlSetExp,'ss',date('Y-m-d H:i',strtotime('+1 year')),$kunderId);
                                    mysqli_stmt_execute($sqlSetExp);
                                    mysqli_stmt_close($sqlSetExp);

                                    header('Location: index.php');
                                }

                            }else{
                                echo'<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> VINI RE: Paisja juaj nuk &eumlsht&euml konfiguruar p&eumlr t&euml p&eumlrdorur programin!</span><br><br>';
                            }
                        }
                        mysqli_stmt_close($sqlVerifikimId);
                    }else{
                        echo'<span id="Label2" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Ju lutemi, vendosni nj&euml li&ccedilens t&euml vlefshme p&eumlr pajisjen!</span><br><br>';

                        $_SESSION['provoPerseri']=$_SESSION['provoPerseri']+1;
                        if($_SESSION['provoPerseri']>2){
                            header('Location: ProvoPerseri.php');
                        }
                    }

                }else{
                    echo'<span id="Label2" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Ju lutemi, vendosni nj&euml li&ccedilens t&euml vlefshme p&eumlr pajisjen!</span><br><br>';
                }
            }catch (Exception $e){
                echo'<td>"PROCESI I VERIFIKIMIT KA PROBLEME"</td>';
                echo'<td>'.$e->getMessage().'</td>';
            }
        }else{
            echo'<span id="Label2" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Ju lutemi, zgjidhni pajisjen t&euml cil&eumln do t&euml licensoni !</span><br><br>';
        }

    }else{
        echo'<span id="Label2" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Ju lutemi, vendosni nj&euml li&ccedilens t&euml vlefshme p&eumlr pajisjen!</span><br><br>';
    }
}else{
    $cookie=$_COOKIE['KUNDERID'];
    $exp_kontrollSql=mysqli_prepare($conn, "SELECT KUNDERID FROM devices WHERE KUNDERID=? AND STATUS=1");

    mysqli_stmt_bind_param($exp_kontrollSql,'s',$_COOKIE['KUNDERID']);
    if(mysqli_stmt_execute($exp_kontrollSql)){

        $rs=mysqli_stmt_get_result($exp_kontrollSql);

        if(mysqli_num_rows($rs)>0){

            while($result=mysqli_fetch_assoc($rs)){
                if($_COOKIE['KUNDERID']!= $result["KUNDERID"]){
                    setcookie("KUNDERID","",time()-3600);
                }else{
                    header('Location: index.php');
                }
            }

        }else{
            setcookie("KUNDERID",$cookie,time()-3600);
        }
    }
}
?>