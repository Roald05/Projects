package com.example.amdoc1;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import android.app.ActionBar;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class MainActivity extends AppCompatActivity  {
    private Button loginBtn;
    TextView signupBtn;
    private EditText userEt,passwEt;
    private Boolean validUser=false;
    private Context ctx=this;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        if (Build.VERSION.SDK_INT >= 21) {
            getWindow().clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
            getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.DarkBlue));
        }

        DatabaseHelper db = new DatabaseHelper(this);
        loginBtn = (Button) findViewById(R.id.login);
        signupBtn = (TextView) findViewById(R.id.signup);

        userEt = (EditText) findViewById(R.id.username);
        passwEt = (EditText) findViewById(R.id.password);

        loginBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(userEt.getText().toString().equals("") || passwEt.getText().toString().equals("")){
                    Toast.makeText(getApplicationContext(),"Plotesoi Username dhe Password !!",Toast.LENGTH_SHORT).show();
                }else{
                    if(userEt.getText().toString().equals("admin") && passwEt.getText().toString().equals("admin")){
                        openTestConn();
                    }else{
                        try{
                            validUser=new MerrUser().execute(userEt.getText().toString(),passwEt.getText().toString()).get();
                        }catch(Exception e){
                            e.printStackTrace();
                        }
                        if(validUser==null){
                            Toast.makeText(getApplicationContext(),"Lidhja me serverin deshtoi!!",Toast.LENGTH_LONG).show();
                        }else{
                            if(validUser){
                                openHome();
                                Toast.makeText(getApplicationContext(),"Perdoruesi u fut me sukses",Toast.LENGTH_SHORT).show();
                            }else{
                                Toast.makeText(getApplicationContext(),"Password ose Username Gabim !!",Toast.LENGTH_LONG).show();
                            }
                        }
                    }
                }
            }
        });

        signupBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try{
                    openSignup();
                }catch(Exception e){
                    e.printStackTrace();
                }
            }
        });
    }

    public void openHome() {
        Intent intent = new Intent(this, Home.class);
        startActivity(intent);
        finish();
    }

    public void openSignup() {
        Intent intent = new Intent(this, Signup.class);
        startActivity(intent);
        finish();
    }

    public void openTestConn() {
        Intent intent = new Intent(this, TestConnection.class);
        startActivity(intent);
        finish();
    }

    private class MerrUser extends AsyncTask<String, Boolean, Boolean> {

        Integer rsSize=0;
        @Override
        protected Boolean doInBackground(String... params) {
            try {

                String user = params[0];
                String pass = params[1];

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
                    String sql = "select * from Patient "
                            + "where username = '" + user + "' "
                            + "and password = '" + pass + "'";
                    Statement statement = conn.createStatement(ResultSet.TYPE_SCROLL_INSENSITIVE, ResultSet.CONCUR_READ_ONLY);
                    ResultSet rs = statement.executeQuery(sql);
                    if(rs != null){
                        rs.last();
                        rsSize=rs.getRow();

                        if(rsSize==1){

                            rs.beforeFirst();

                            while (rs.next()) {
                                UserCredentials.PATIENT_ID = rs.getInt("id");
                                UserCredentials.DOCTOR_ID = rs.getString("doctor_id");
                                UserCredentials.PATIENT_NAME = rs.getString("name");
                                UserCredentials.PATIENT_SURNAME = rs.getString("lastName");
                                UserCredentials.ADDRESS = rs.getString("address");
                                UserCredentials.GENDER = rs.getString("gender");
                                UserCredentials.PHONE = rs.getString("phone");
                                UserCredentials.USERNAME = rs.getString("username");
                                UserCredentials.PASSWORD = rs.getString("password");
                            }
                            return true;

                        }else{
                            return false;
                        }
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        protected void onPostExecute(Boolean userExist) {
            if(!userExist && rsSize>1){
                Toast.makeText(getApplicationContext(),"Ka me shume se 1 Pacient me keto kredenciale!!",Toast.LENGTH_LONG).show();
            }
        }
    }




}
