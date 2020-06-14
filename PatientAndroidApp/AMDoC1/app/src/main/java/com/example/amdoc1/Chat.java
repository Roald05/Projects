package com.example.amdoc1;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.ContextCompat;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;

public class Chat extends AppCompatActivity implements View.OnClickListener {

    private Context ctx=this;
    private Boolean kaPergjije=false;
    Button btPyetja1,btPyetja2,btPyetja3,btPyetja4,btPyetja5,btPyetja6;
    private String Mesazhi;
    TextView v;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.chat_layout);
        setTitle("Komuniko me doktorin tend");

        btPyetja1 = (Button) findViewById(R.id.btPyetja1);
        btPyetja1.setOnClickListener(this);
        btPyetja2 = (Button) findViewById(R.id.btPyetja2);
        btPyetja2.setOnClickListener(this);
        btPyetja3 = (Button) findViewById(R.id.btPyetja3);
        btPyetja3.setOnClickListener(this);
        btPyetja4 = (Button) findViewById(R.id.btPyetja4);
        btPyetja4.setOnClickListener(this);
        btPyetja5 = (Button) findViewById(R.id.btPyetja5);
        btPyetja5.setOnClickListener(this);
        btPyetja6 = (Button) findViewById(R.id.btPyetja6);
        btPyetja6.setOnClickListener(this);

        if (Build.VERSION.SDK_INT >= 21) {
            getWindow().clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
            getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.DarkBlue));
        }
        // toolbar
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        FloatingActionButton fab = findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                showAlertDialogButtonClicked(view);
            }
        });

        // add back arrow to toolbar
        if (getSupportActionBar() != null){
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#000e1a")));
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }
    }

    public void showAlertDialogButtonClicked(View view) {
        // create an alert builder
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Shkruaj nje mesazh per doktorin");

        // set the custom layout
        final View customLayout = getLayoutInflater().inflate(R.layout.alert_layout, null);
        builder.setView(customLayout);
        // add a button
        builder.setPositiveButton("Dergo", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                // send data from the AlertDialog to the Activity
                EditText editText = customLayout.findViewById(R.id.editText);
                sendDialogDataToActivity(editText.getText().toString());
            }
        });
        // create and show the alert dialog
        AlertDialog dialog = builder.create();
        dialog.getWindow().setBackgroundDrawableResource(R.color.AMDoc_Color);
        dialog.show();
    }

    // do something with the data coming from the AlertDialog
    private void sendDialogDataToActivity(String data) {
        try{
            kaPergjije=new MerrNgaServer().execute().get();
            if(!kaPergjije){
                new FutNeServer().execute(data);
            }else{
                Toast toast = Toast.makeText(this, "Nuk mund te beni nje pyetje pa marr pergjigje per pyetjen e fundit", Toast.LENGTH_SHORT);
                v = (TextView) toast.getView().findViewById(android.R.id.message);
                v.setTextColor(Color.parseColor("#D50000"));
                toast.show();
            }
        }catch(Exception e){
            e.printStackTrace();
        }

    }

    @Override
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.btPyetja1 :
                Mesazhi=btPyetja1.getText().toString();
                break;
            case R.id.btPyetja2 :
                Mesazhi=btPyetja2.getText().toString();
                break;
            case R.id.btPyetja3 :
                Mesazhi=btPyetja3.getText().toString();
                break;
            case R.id.btPyetja4 :
                Mesazhi=btPyetja4.getText().toString();
                break;
            case R.id.btPyetja5 :
                Mesazhi=btPyetja5.getText().toString();
                break;
            case R.id.btPyetja6 :
                Mesazhi=btPyetja6.getText().toString();
                break;
        }

        try{
            kaPergjije=new MerrNgaServer().execute().get();
            if(!kaPergjije){
                new FutNeServer().execute(Mesazhi);
            }else{
                Toast toast = Toast.makeText(this, "Nuk mund te beni nje pyetje pa marr pergjigje per pyetjen e fundit", Toast.LENGTH_LONG);
                v = (TextView) toast.getView().findViewById(android.R.id.message);
                v.setTextColor(Color.parseColor("#D50000"));
                toast.show();
            }

        }catch(Exception e){
            e.printStackTrace();
        }
    }



    private class FutNeServer extends AsyncTask<String, Void, Void> {

        private Boolean inserted=false;
        @Override
        protected Void doInBackground(String... params) {
            try {

                String kerkimiParam=params[0];

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
                    Date c = Calendar.getInstance().getTime();
                    System.out.println("Current time => " + c);

                    SimpleDateFormat df = new SimpleDateFormat("dd-MMM-yyyy");
                    String formattedDate = df.format(c);

                    String sql = "INSERT INTO Chat (Mesazhi,doctor_id,patient_id,seen,type,date_created) VALUES ('" + kerkimiParam + "','" + UserCredentials.DOCTOR_ID + "'," + UserCredentials.PATIENT_ID + ",0,'Q',CURRENT_TIMESTAMP)";

                    Statement statement = conn.createStatement();

                    if (statement.executeUpdate(sql)!=-1) {
                        inserted=true;
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        protected void onPostExecute(Void v) {
            if(inserted){
                Toast.makeText(Chat.this, "Mesazhi u dergua", Toast.LENGTH_SHORT).show();
            }
        }
    }


    private class MerrNgaServer extends AsyncTask<Integer, Boolean, Boolean> {
        private Integer count=0;
        @Override
        protected Boolean doInBackground(Integer... params) {
            try {

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

                    String sql="select * from Chat where patient_Id = " + UserCredentials.PATIENT_ID + " and doctor_Id = '" + UserCredentials.DOCTOR_ID + "' and type = 'Q' and seen=0 order by date_created desc";

                    Statement statement = conn.createStatement();
                    ResultSet resultSet = statement.executeQuery(sql);

                    while (resultSet.next()) {
                        count++;
                        return true;
                    }

                    if(count<=0){
                        return false;
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }
    }

}
