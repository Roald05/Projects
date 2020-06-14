<?php
try {
    if (isset($_POST['loginBtn'])) {
        $logInValidationQuery = mysqli_prepare($conn, "SELECT USERNAMED,FJALEKALIM,TIPI FROM users WHERE USERNAMED = ? AND STATUS=1 ");
        mysqli_stmt_bind_param($logInValidationQuery, 's', $_POST['usernameDDown']);
        if (mysqli_stmt_execute($logInValidationQuery)) {
            $rs = mysqli_stmt_get_result($logInValidationQuery);
            if (mysqli_num_rows($rs) > 0) {
                while ($result = mysqli_fetch_assoc($rs)) {
                    if (isset($_POST['usernameDDown']) && isset($_POST['passwordTxt'])) {
                        if ($_POST['passwordTxt'] === $result['FJALEKALIM']) {
                            $_SESSION['uStatus'] = $result['TIPI'];

                            if($result['TIPI']=='S'){
                                $_SESSION['perdoruesi'] = $_POST['usernameDDown'];
                                $_SESSION['fjalekalimi'] = $_POST['passwordTxt'];

                                header('Location: SetBusiness.php');
                            }elseif($result['TIPI']=='A'){
                                $_SESSION['perdoruesi'] = $_POST['usernameDDown'];
                                $_SESSION['fjalekalimi'] = $_POST['passwordTxt'];

                                header('Location: SetBusiness.php');
                            }elseif($result['TIPI']=='B'){

                                if(!isset($_COOKIE['KUNDERID'])){
                                    header("Location: SetCookie.php");
                                }else{
                                    $_SESSION['perdoruesi'] = $_POST['usernameDDown'];
                                    $_SESSION['fjalekalimi'] = $_POST['passwordTxt'];

                                    header('Location: Filialet.php');
                                }
                            }

                        } else if ($_POST['passwordTxt'] !== $result["FJALEKALIM"]) {
                            $_SESSION['userId']=$_POST["usernameDDown"];
                            echo '<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Fjal&eumlkalimi &eumlsht&euml gabim!</span>';
                            break;
                        } else {
                            echo '<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Ky p&eumlrdorues nuk egziston!</span>';
                            break;
                        }
                    } else {
                        echo '<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Plot&eumlsoni te dyja hapsirat!</span>';
                        break;
                    }
                }
            } else {
                echo '<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Fut p&eumlrdoruesin!</span>';

            }
        }
    }
}catch (Exception $e){
    echo'<td>"PROCESI I VERIFIKIMIT KA PROBLEME KONTROLLO LIDHJEN ME INTERNETIN"</td>';
    echo'<td>'.$e->getMessage().'</td>';
}
?>