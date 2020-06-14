<?php
ini_set('memory_limit', -1);
if(isset($_POST['markerId'])){
    $mId=$_POST['markerId'];
    $where="students.MarkerId=".$mId."";
}else{
    $mId=0;
}
?>
<?php
include "../include/loginValidation.php";
include "../include/fileExist.php";
include "../dbconn/conn.php";
?>
<!DOCTYPE html>
<html lang="en" >
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <title>Meeting Schedule</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet"> 
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&display=swap" rel="stylesheet"> <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome-font-awesome.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/css/datepicker.css'><link rel="stylesheet" href="./style.css">

</head>
<body>
<!-- partial:index.partial.html -->
<div class="p-5">
  <h2 class="mb-4">Full Schedule</h2>
  <div class="card">
    <div class="card-body p-0">
      <div id="calendar"></div>
    </div>
  </div>
</div>

<!-- calendar modal -->
<div id="modal-view-event" class="modal modal-top fade calendar-modal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<h4 class="modal-title"><span class="event-icon"></span><span class="event-title"></span></h4>
					<div class="event-body"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

<div id="modal-view-event-add" class="modal modal-top fade calendar-modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="add-event">
        <div class="modal-body">
        <h4>Add Event Detail</h4>
            <div class="form-group">
                <label> Supervisor </label>
                <select class="form-control supervisor"  id="supervisorId" name="eSup">
                    <option value=-1 selected>No selected</option>
                    <?php

                    $sqlQ = "SELECT * FROM supervisors";
                    $result = $conn->query($sqlQ);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo'<option value='.$row["SupervisorId"].'>'.$row["SupervisorName"].' '.$row["SupervisorSurname"].'</option>';

                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label> Marker </label>
                <select class="form-control marker"  id="markerId" name="eSup">
                    <option value=-1 selected>No selected</option>
                    <?php

                    $sqlQ = "SELECT * FROM marker";
                    $result = $conn->query($sqlQ);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo'<option value='.$row["MarkerId"].'>'.$row["MarkerName"].' '.$row["MarkerSurname"].'</option>';

                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label> Student (Student : Module : Supervisor : Marker) </label>
                <select class="form-control student"  id="studentId" name="eSup">
                    <option value='-1' selected>No selected</option>
                    <?php

                        $sqlQ = "SELECT * FROM students s inner join studentsmodule sm on s.StudentId=sm.StudentId";

                        $result = $conn->query($sqlQ);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo'<option value='.$row["StudentId"].'>'.$row["StudentName"].' '.$row["StudentSurname"].' : '.$row["ModuleCode"].' : '.$row["SupervisorName"].' : '.$row["MarkerName"].'</option>';

                            }
                        }
                    ?>
                </select>
            </div>
          <div class="form-group">
            <label>Event name</label>
            <input type="text" id="eventName" class="form-control" name="ename">
          </div>
          <div class="form-group" id="ed">
            <label>Event Date</label>
            <input type='text' id="eventDate" class="datetimepicker form-control" name="edate">
          </div>        
          <div class="form-group">
            <label>Event Description</label>
            <textarea class="form-control"  id="eventDesc" name="edesc"></textarea>
          </div>
          <div class="form-group">
            <label>Event Color</label>

              <input type='color' id="eventColor" class="form-control" name="ecolor">
          </div>
      </div>
        <div class="modal-footer">
        <button type="button" id="saveBTN" class="btn btn-primary" >Save</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>        
      </div>
      </form>
    </div>
  </div>
</div>
<!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/datepicker.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/air-datepicker/2.2.3/js/i18n/datepicker.en.js'></script><script  src="./script.js"></script>

</body>
</html>