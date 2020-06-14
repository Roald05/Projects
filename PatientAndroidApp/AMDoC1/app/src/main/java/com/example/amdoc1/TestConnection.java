package com.example.amdoc1;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

public class TestConnection extends AppCompatActivity {

    private  Button btTesto,btRuaj,btDil;
    private EditText etIp,etPorta,etDb,etUsername,etPassword;
    private boolean connExists=false;
    private Context cx=this;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.testconnection_layout);

         btTesto=(Button) findViewById(R.id.btTesto);
         btRuaj=(Button) findViewById(R.id.btRuaj);
         btDil=(Button) findViewById(R.id.btDil);

         etIp=(EditText) findViewById(R.id.etIp);
         etPorta=(EditText) findViewById(R.id.etPorta);
         etDb=(EditText) findViewById(R.id.etDb);
         etUsername=(EditText) findViewById(R.id.etUser);
         etPassword=(EditText) findViewById(R.id.etPass);

        DatabaseHelper db = new DatabaseHelper(this);
        db.getReadableDatabase();

        if(!db.getIp().equals("")){
            etIp.setText(db.getIp());
            etPorta.setText(db.getPort());
            etDb.setText(db.getDbName());
            etUsername.setText(db.getDbusername());
            etPassword.setText(db.getDbpassword());
        }

        btDil.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openMain();
            }
        });

        btRuaj.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                DatabaseHelper db = new DatabaseHelper(cx);

                if(etIp.getText().toString().equals("") || etPorta.getText().toString().equals("") || etDb.getText().toString().equals("") || etUsername.getText().toString().equals("") || etPassword.getText().toString().equals("")){

                    Toast.makeText(getApplicationContext(),"Plotesoni te gjitha fushat!",Toast.LENGTH_SHORT).show();

                }else{
                    db.addServerCredetnials(etIp.getText().toString(),etPorta.getText().toString(),etDb.getText().toString(),etUsername.getText().toString(),etPassword.getText().toString());

                    etIp.setText("");
                    etPorta.setText("");
                    etDb.setText("");
                    etUsername.setText("");
                    etPassword.setText("");

                    Toast.makeText(getApplicationContext(),"Te dhenat u ruajten me sukses!",Toast.LENGTH_SHORT).show();
                }
            }
        });

        btTesto.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Toast.makeText(getApplicationContext(),"Lidhja po testohet...",Toast.LENGTH_SHORT).show();
                new TestoLidhjen().execute(etIp.getText().toString()+":"+etPorta.getText().toString(),etDb.getText().toString(),etUsername.getText().toString(),etPassword.getText().toString());
            }
        });

    }

    public void openMain() {
        Intent intent = new Intent(this, MainActivity.class);
        startActivity(intent);
        finish();
    }

    private class TestoLidhjen extends AsyncTask<String, Boolean, Boolean> {

        @Override
        protected Boolean doInBackground(String... params) {
            try {

                String server = params[0];
                String db = params[1];
                String username = params[2];
                String password = params[3];

                Class.forName("net.sourceforge.jtds.jdbc.Driver").newInstance();
                String connUrl="jdbc:jtds:sqlserver://" + server + ";" + "databaseName=" + db + ";user=" + username + ";password=" + password + ";";
                DriverManager.setLoginTimeout(3);
                Connection conn = DriverManager.getConnection(connUrl);

                connExists=true;
            } catch (Exception e) {
                connExists=false;
                e.printStackTrace();
            }
            return null;
        }

        @Override
        protected void onPostExecute(Boolean aBoolean) {
            super.onPostExecute(aBoolean);
            if (connExists){
                Toast.makeText(getApplicationContext(),"Pajisja lidhet me sukses !",Toast.LENGTH_SHORT).show();
            }else{
                Toast.makeText(getApplicationContext(),"Lidhja deshtoi",Toast.LENGTH_SHORT).show();
            }
        }
    }
}
