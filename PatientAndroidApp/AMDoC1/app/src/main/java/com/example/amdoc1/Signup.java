package com.example.amdoc1;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.Statement;

public class Signup extends AppCompatActivity {

    private Button signupBtn;
    TextView loginBtn;
    private EditText userEt,passwEt,confirm,telefon,addresa,emri,mbiemri,doktori;
    private RadioGroup radiobutonat;
    private RadioButton gjinia;
    private Boolean validUser=false;
    private Context ctx=this;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);
        if (Build.VERSION.SDK_INT >= 21) {
            getWindow().clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
            getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.DarkBlue));
        }

        DatabaseHelper db = new DatabaseHelper(this);
        loginBtn = (TextView) findViewById(R.id.signin);
        signupBtn = (Button) findViewById(R.id.signup);

        userEt = (EditText) findViewById(R.id.username);
        passwEt = (EditText) findViewById(R.id.password);
        confirm =(EditText) findViewById(R.id.confirm);
        telefon =(EditText) findViewById(R.id.telefoni);
        addresa =(EditText) findViewById(R.id.addresa);
        emri =(EditText) findViewById(R.id.emer);
        mbiemri =(EditText) findViewById(R.id.mbiemer);
        radiobutonat = (RadioGroup) findViewById(R.id.gjinia);
        doktori = (EditText) findViewById(R.id.doktori);

        signupBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                gjinia = (RadioButton)findViewById(radiobutonat.getCheckedRadioButtonId());

                if(userEt.getText().toString().equals("") || passwEt.getText().toString().equals("")|| doktori.getText().toString().equals("") || gjinia.getText().toString().equals(null) || confirm.getText().toString().equals("") || emri.getText().toString().equals("") || mbiemri.getText().toString().equals("") || telefon.getText().toString().equals("")  || addresa.getText().toString().equals("")){
                    Toast.makeText(getApplicationContext(),"Plotesoi te gjitha fushat !!",Toast.LENGTH_SHORT).show();
                }else{
                    if(userEt.getText().toString().equals("admin") && passwEt.getText().toString().equals("admin")){
                        openTestConn();
                    }else if ( !passwEt.getText().toString().equals(confirm.getText().toString())){
                        Toast.makeText(getApplicationContext(),"Passwordi nuk eshte i njejte !!",Toast.LENGTH_SHORT).show();
                    }
                    else{
                        try{
                            validUser=new Fut_User().execute(userEt.getText().toString(),passwEt.getText().toString(),emri.getText().toString(),mbiemri.getText().toString(),addresa.getText().toString(),telefon.getText().toString(),doktori.getText().toString(),gjinia.getText().toString()).get();
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

        loginBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try{
                    openSignin();
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

    public void openSignin() {
        Intent intent = new Intent(this, MainActivity.class);
        startActivity(intent);
        finish();
    }

    public void openTestConn() {
        Intent intent = new Intent(this, TestConnection.class);
        startActivity(intent);
        finish();
    }

    private class Fut_User extends AsyncTask<String, Boolean, Boolean> {

        Integer rsSize=0;
        @Override
        protected Boolean doInBackground(String... params) {
            try {

                String user = params[0];
                String pass = params[1];
                String emer = params[2];
                String mbiemer = params[3];
                String adrres = params[4];
                String telefon = params[5];
                String doktori = params[6];
                String gjinia = params[7];

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
                    String sql = "Insert Into Patient (name,lastName,address,gender,phone,deleted,username,password,doctor_id) values ('"+ emer +"','"+ mbiemer+"','"+ adrres+"','"+ gjinia +"',"+ telefon+",0,'"+ user+"','"+ pass+"','"+ doktori +"')";
                    Statement statement = conn.createStatement();
                    if(statement.executeUpdate(sql)!=-1){
                        return true;
                    }else{
                        return false;
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        protected void onPostExecute(Boolean inserted) {
            if(inserted){
                Toast.makeText(getApplicationContext(),"Pacienti u regjistrua me sukses!!",Toast.LENGTH_LONG).show();
            }
        }
    }

}
