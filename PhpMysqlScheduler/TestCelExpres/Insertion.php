<?php
session_start();
?>
<?php
include 'SupervisorInsertion.php';
include 'MarkerInsertion.php';
include 'StudentInsertion.php';
include 'StudentEvents.php';
header("Location: calendar/index.php");
?>
