# PhpScheduler


This is a Scheduler programmed in php and mysql 

<h3>Description and analysis:</h3>
The purpose of this scheduler is to generate timeslots where meetings can occur between students and their respective supervisors regarding the final project of the year.

So there are 4 types of users on the system: 'ProjectAdmin','Marker','Supervisor','Student'.
ProjectAdmin: It has all the rights in the system can run a scheduler and also create new users
Marker: It can create a schedule , also can view only theirs.
Supervisor: Can only view their respective schedule.
Student : Can only view their respective schedule.

<h3>Technical details:</h3>
So the meetings beetwen the student and the supervisor can occur only once same as student - marker.
The meetings should be as near as possible because markers and supervisor should take par in as many meeting as possibler as long as they are in the university.
Ofcourse the meetings should not conflict with the students timetable.

<h3>Workflow:</h3>
The projects admin should upload 3 files : Supervisor.csv,Marker.csv,Student.csv,Timetable.html
After these files are uploded , the respective users are cereated and also the credentials are send to all the entities in the system.
And then they can log in and create shcedules.
