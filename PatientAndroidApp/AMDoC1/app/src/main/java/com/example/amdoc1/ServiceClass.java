package com.example.amdoc1;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Build;
import android.os.IBinder;
import android.os.Handler;
import android.util.Log;
import android.widget.TextView;
import android.widget.Toast;

import androidx.core.app.NotificationCompat;
import androidx.core.app.NotificationManagerCompat;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.Timer;
import java.util.TimerTask;


public class ServiceClass extends Service {
    Context ctx=this;
    public static final String CHANNEL_1_ID = "channel1";

    public static final int notify = 30000;  //interval between two services(Here ServiceClass run every 5 Minute)
    private Handler mHandler = new Handler() {};   //run on another Thread to avoid crash
    private Timer mTimer = null;    //timer handling

    @Override
    public IBinder onBind(Intent intent) {
        throw new UnsupportedOperationException("Not yet implemented");
    }

    @Override
    public void onCreate() {
        if (mTimer != null) // Cancel if already existed
            mTimer.cancel();
        else
            mTimer = new Timer();   //recreate new
        mTimer.scheduleAtFixedRate(new TimeDisplay(), 0, notify);   //Schedule task
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        mTimer.cancel();    //For Cancel Timer
        Log.d("service is ","Destroyed");
    }

    //class TimeDisplay for handling task
    class TimeDisplay extends TimerTask {
        @Override
        public void run() {
            // run on another thread
            mHandler.post(new Runnable() {
                @Override
                public void run() {
                    new MerrPergjigjeNgaServer().execute();
                }
            });
        }
    }

    // Creates and displays a notification
    private void addNotification(String title,String message,String pyetja,String date1,String date2) {
        // Builds your notification
        if (android.os.Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {

        }
        NotificationCompat.Builder builder = new NotificationCompat.Builder(this,CHANNEL_1_ID)
                .setSmallIcon(R.mipmap.ic_logo_round)
                .setContentTitle(title)
                .setContentText(message);

        // Creates the intent needed to show the notification
        Intent notificationIntent = new Intent(this, NotificationDialog.class);
        notificationIntent.putExtra("date1", date1);
        notificationIntent.putExtra("pyetja", pyetja);
        notificationIntent.putExtra("date2", date2);
        notificationIntent.putExtra("message", message);
        PendingIntent contentIntent = PendingIntent.getActivity(this, 0, notificationIntent, PendingIntent.FLAG_UPDATE_CURRENT);
        builder.setContentIntent(contentIntent);

        // Add as notification
        NotificationManager manager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        manager.notify(0, builder.build());
    }


    private void createNotificationChannels() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel1 = new NotificationChannel(
                    CHANNEL_1_ID,
                    "Channel 1",
                    NotificationManager.IMPORTANCE_HIGH
            );
            channel1.setDescription("This is Channel 1");

            NotificationManager manager = getSystemService(NotificationManager.class);
            manager.createNotificationChannel(channel1);
        }
    }


    private class MerrPergjigjeNgaServer extends AsyncTask<Integer, Void, Void> {
        private Integer count=0;
        private String mesazhi="",pyetja="",date1="",date2="";
        private Boolean updated=false;
        @Override
        protected Void doInBackground(Integer... params) {
            try {
                String sql;
                Statement statement;
                ResultSet resultSet;

                Class.forName("net.sourceforge.jtds.jdbc.Driver").newInstance();
                DatabaseHelper dbH=new DatabaseHelper(ctx);
                String connUrl=dbH.merrServer();
                DriverManager.setLoginTimeout(2);
                Connection conn = DriverManager.getConnection(connUrl);

                if (conn == null)
                {
                    return null;
                }
                else{
                    sql="select top 1 Mesazhi,date_created from Chat where patient_Id = " + UserCredentials.PATIENT_ID + " and doctor_Id = '" + UserCredentials.DOCTOR_ID + "' and type = 'A' and seen=0 order by date_created desc";

                    statement = conn.createStatement();
                    resultSet = statement.executeQuery(sql);

                    while (resultSet.next()) {
                        mesazhi=resultSet.getString("Mesazhi");
                        date2=resultSet.getString("date_created");
                    }

                    statement.close();

                    sql="select top 1 Mesazhi,date_created from Chat where patient_Id = " + UserCredentials.PATIENT_ID + " and doctor_Id = '" + UserCredentials.DOCTOR_ID + "' and type = 'Q' and seen=1 order by date_created desc";

                    statement = conn.createStatement();
                    resultSet = statement.executeQuery(sql);

                    while (resultSet.next()) {
                        pyetja=resultSet.getString("Mesazhi");
                        date1=resultSet.getString("date_created");
                    }

                    statement.close();

                    if(!mesazhi.equals("")){
                        sql="update Chat set seen=1 where patient_Id = " + UserCredentials.PATIENT_ID + " and doctor_Id = '" + UserCredentials.DOCTOR_ID + "' and type = 'A' and seen=0";

                        statement = conn.createStatement();

                        if (statement.executeUpdate(sql)!=-1) {
                            updated=true;
                        }
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }

            return null;
        }


        protected void onPostExecute(Void Void) {
            if(!mesazhi.equals("")&&!date2.equals("")&&!pyetja.equals("")&&!date1.equals("")&&updated){
                createNotificationChannels();
                addNotification("Pergjigja e pyetjes tuaj :",mesazhi,pyetja,date1,date2);

            }/*else{
                TextView v;
                Toast toast = Toast.makeText(ServiceClass.this, "Pati nje problem ne marrjen e pergjigjes se doktorit !", Toast.LENGTH_LONG);
                v = (TextView) toast.getView().findViewById(android.R.id.message);
                v.setTextColor(Color.parseColor("#D50000"));
                toast.show();
            }*/
        }
    }
}
