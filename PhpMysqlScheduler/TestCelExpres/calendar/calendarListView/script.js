document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'list' ],
    timeZone: 'UTC',
    defaultView: 'listWeek',

    // customize the button names,
    // otherwise they'd all just say "list"
    views: {
      listDay: { buttonText: 'list day' },
      listWeek: { buttonText: 'list week' },
      listMonth: { buttonText: 'list month' }
    },
    editable: true,
    customButtons: {
      myCustomButton: {
        text: 'Back',
        click: function() {
          history.back();
          //$(location).attr('href', '..\\calendarListView\\script.js');
        }
      }
    },
    header: {
      left: 'title,myCustomButton',
      center: '',
      right: 'listDay,listWeek,listMonth'
    },
    events: 'load.php'
  });

  calendar.render();
});