<?php
if(session_status() === PHP_SESSION_NONE){
    //session_regenerate_id();
    session_start();
}

?>