jQuery(document).ready(function(){
  jQuery('.datetimepicker').datepicker({
      timepicker: true,
      language: 'en',
      range: true,
      multipleDates: true,
		  multipleDatesSeparator: " - "
    });
});

(function () {    
    'use strict';
    // ------------------------------------------------------- //
    // Calendar
    // ------------------------------------------------------ //
	jQuery(function() {
		// page is ready
		jQuery('#calendar').fullCalendar({
			themeSystem: 'bootstrap4',
			// emphasizes business hours
			businessHours: false,
			defaultView: 'month',
			// event dragging & resizing
			editable: true,
			customButtons: {
				myCustomButton: {
					text: 'List View',
					click: function() {
						$(location).attr('href', 'calendarListView/index.php');
					}
				},
				backBtn: {
					text: 'Back',
					click: function() {
						var loc = window.location.pathname;
						var dir = loc.substring(0, loc.lastIndexOf('/')-8);
						$(location).attr('href', dir+'MainMenu.php');
					}
				}
			},

			// header
			header: {
				left: 'title',
				center: 'backBtn,month,agendaWeek,agendaDay,myCustomButton',
				right: 'today prev,next'
			},
			events: 'load.php',
			eventRender: function(event, element) {
				if(event.icon){
					element.find(".fc-title").prepend("<i class='fa fa-"+event.icon+"'></i>");
				}
			  },
			eventResize:function(event)
			{
				var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
				var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
				var desc = event.description;
				var id = event.id;
				$.ajax({
					url:"update.php",
					type:"POST",
					data:{start:start, end:end, id:id, desc:desc},
					success:function(){
						//calendar.fullCalendar('refetchEvents');
						//alert(start+end+id);
						alert("Event succesfully updated");
					}
				})
			},
			eventDrop:function(event)
			{
				var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
				var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
				var desc = event.description;
				var id = event.id;
				$.ajax({
					url:"update.php",
					type:"POST",
					data:{start:start, end:end, id:id,  desc:desc},
					success:function()
					{
						//calendar.fullCalendar('refetchEvents');
						//alert(start+end+id);
						alert("Event succesfully updated");
					}
				});
			},

			dayClick: function() {
				jQuery('#modal-view-event-add').modal();

				document.getElementById("supervisorId").disabled=false;
				document.getElementById("markerId").disabled=false;
				document.getElementById("studentId").disabled = supervisorId.options[supervisorId.selectedIndex].value === '-1' && markerId.options[markerId.selectedIndex].value === '-1';

				$("select.supervisor").change( function(e){
					var supervisorId=document.getElementById("supervisorId");

					document.getElementById("markerId").disabled = supervisorId.options[supervisorId.selectedIndex].value !== '-1';

					document.getElementById("studentId").disabled = supervisorId.options[supervisorId.selectedIndex].value === '-1' && markerId.options[markerId.selectedIndex].value === '-1';

				});
				$("select.marker").change( function(e){

				    var markerId=document.getElementById("markerId");

				    document.getElementById("supervisorId").disabled = markerId.options[markerId.selectedIndex].value !== '-1';

					document.getElementById("studentId").disabled = supervisorId.options[supervisorId.selectedIndex].value === '-1' && markerId.options[markerId.selectedIndex].value === '-1';

				});

				$("#saveBTN").click(function(){

					var supId=document.getElementById("supervisorId");
					var mId=document.getElementById("markerId");
					var stId=document.getElementById("studentId");
					var eName=document.getElementById("eventName").value;
					var eDesc=document.getElementById("eventDesc").value;
					var eDate=document.getElementById("eventDate").value;
					var eColor=document.getElementById("eventColor").value;

					if(( supId.options[supId.selectedIndex].value==='-1' && mId.options[mId.selectedIndex].value==='-1')||stId.options[stId.selectedIndex].value==='-1'||eName===''||eDesc===''||eDate===''){
						alert("All fields are required");
					}else{
						if(eDate.includes("-")){
							alert("Event must be set on one date only");
						}else{
                            var dt = new Date(eDate);
                            var hours = dt.getHours(); // gives the value in 24 hours format
                            var minutes = dt.getMinutes() ;
                            var etime = hours + ":" + minutes;

							if(mId.options[mId.selectedIndex].value==='-1'){
                                mId=0;
								$.ajax({
									url:"insert.php",
									type:"POST",
									data:{supId:supId.options[supId.selectedIndex].value,mId:mId,stId:stId.options[stId.selectedIndex].value, eName:eName, eDesc:eDesc, eDate:eDate, etime:etime, eColor:eColor},
									success:function(data)
									{
										//calendar.fullCalendar('refetchEvents');
										alert("Added Successfully");
										//alert(supId.options[supId.selectedIndex].value+' '+eName+' '+eDesc+' '+' '+eDate+' '+etime+' '+eColor);
										//alert(data);

									}
								})
							}else{
                                supId=0;
								$.ajax({
									url:"insert.php",
									type:"POST",
									data:{supId:supId,mId:mId.options[mId.selectedIndex].value,stId:stId.options[stId.selectedIndex].value, eName:eName, eDesc:eDesc, eDate:eDate, etime:etime, eColor:eColor},
									success:function(data)
									{
										//calendar.fullCalendar('refetchEvents');
										alert("Added Successfully");
										//alert(mId.options[mId.selectedIndex].value+' '+eName+' '+eDesc+' '+' '+eDate+' '+etime+' '+eColor);
										//alert(data);
									}
								})
							}
						}
					}
				})
			},
			eventClick: function(event, jsEvent, view) {
			        jQuery('.event-icon').html("<i class='fa fa-"+event.icon+"'></i>");
					jQuery('.event-title').html(event.title);
					jQuery('.event-body').html(event.description);
					jQuery('.eventUrl').attr('href',event.url);
					jQuery('#modal-view-event').modal();
			},
		})
	});
  
})(jQuery);