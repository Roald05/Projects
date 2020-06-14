package com.example.amdoc1;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.view.WindowManager;
import android.widget.CalendarView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.ContextCompat;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class Details extends AppCompatActivity {
    TextView tvTitulli1,tvTitulli2,tvTitulli3,tvTitulli4,tvTitulli5,tvTitulli6,tvTitulli7,tvTitulli8,tvTitulli9;
    TextView tvElement1,tvElement2,tvElement3,tvElement4,tvElement5,tvElement6,tvElement7,tvElement8,tvElement9;
    private Context ctx=this;
    private Integer id=0;
    private String tipi="";
    private ResultSet rs;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.details_layout);

         tvTitulli1 = (TextView) findViewById(R.id.tvTitulli1);
         tvTitulli2 = (TextView) findViewById(R.id.tvTitulli2);
         tvTitulli3 = (TextView) findViewById(R.id.tvTitulli3);
         tvTitulli4 = (TextView) findViewById(R.id.tvTitulli4);
         tvTitulli5 = (TextView) findViewById(R.id.tvTitulli5);
         tvTitulli6 = (TextView) findViewById(R.id.tvTitulli6);
         tvTitulli7 = (TextView) findViewById(R.id.tvTitulli7);
         tvTitulli8 = (TextView) findViewById(R.id.tvTitulli8);
         tvTitulli9 = (TextView) findViewById(R.id.tvTitulli9);


         tvElement1 = (TextView) findViewById(R.id.tvElement1);
         tvElement2 = (TextView) findViewById(R.id.tvElement2);
         tvElement3 = (TextView) findViewById(R.id.tvElement3);
         tvElement4 = (TextView) findViewById(R.id.tvElement4);
         tvElement5 = (TextView) findViewById(R.id.tvElement5);
         tvElement6 = (TextView) findViewById(R.id.tvElement6);
         tvElement7 = (TextView) findViewById(R.id.tvElement7);
         tvElement8 = (TextView) findViewById(R.id.tvElement8);
         tvElement9 = (TextView) findViewById(R.id.tvElement9);


        Bundle bundle = getIntent().getExtras();
        id=bundle.getInt("id");
        if (bundle != null) {
            switch(bundle.getString("tipi")){
                case "lr":
                    setTitle("Receta");
                    tipi="lr";
                    break;
                case "hm":
                    setTitle("Kartela Mjeksore");
                    tipi="hm";
                    break;
                case "il":
                    setTitle("Detaje per ilacin");
                    tipi="il";
                    break;
            }
        }

        new MerrNgaServer().execute(id);

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
                Intent intent = new Intent(Details.this, Chat.class);
                startActivity(intent);
            }
        });

        // add back arrow to toolbar
        if (getSupportActionBar() != null){
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#000e1a")));
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }
    }

    @Override
    public boolean onSupportNavigateUp() {
        Intent intent = new Intent(getApplicationContext(), Listime.class);
        String Tipi="";
        switch(tipi){
            case "lr":
                Tipi="Lista Recetave";
                break;
            case "hm":
                Tipi="Historiku";
                break;
            case "il":
                Tipi="Ilace";
                break;
        }
        intent.putExtra("Tipi", Tipi);
        startActivity(intent);
        finish();
        return true;
    }

    private class MerrNgaServer extends AsyncTask<Integer, Void, Void> {
        private Integer count=0;
        @Override
        protected Void doInBackground(Integer... params) {
            try {

                Integer kerkimiParam=params[0];

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

                    String sql="";

                    switch(tipi){
                        case "lr":
                            sql = "select * from Perscription where patient_Id = " + UserCredentials.PATIENT_ID + " and Id = " + kerkimiParam + "";
                            break;
                        case "hm":
                            sql = "select * from Patient_medical_chart where patient_Id = " + UserCredentials.PATIENT_ID + " and Id = " + kerkimiParam + "";
                            break;
                        case "il":
                            sql = "select * from ilace where Id = " + kerkimiParam + "";
                            break;
                    }

                    Statement statement = conn.createStatement();
                    ResultSet resultSet = statement.executeQuery(sql);

                    while (resultSet.next()) {
                        count++;
                        break;
                    }

                    if(count>0){
                        rs = resultSet;
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        protected void onPostExecute(Void v) {
            if(count > 0){
                switch(tipi){
                    case "lr":
                        tvTitulli1.setText("Data Nenshkrimit : ");
                        tvTitulli2.setText("Preparati : ");
                        tvTitulli3.setText("Dosa (ne dite) : ");
                        tvTitulli4.setText("Pershkrimi : ");
                        tvTitulli5.setText("Data fillimit te pushimit : ");
                        tvTitulli6.setText("Data mbarimit te pushimit : ");
                        tvTitulli7.setText("Id Doktorit : ");
                        tvTitulli8.setText("Id Pacientit : ");
                        tvTitulli9.setText("Semundja : ");


                        try {
                            tvElement1.setText(rs.getString("date_created"));
                            tvElement2.setText(rs.getString("medicine"));
                            tvElement3.setText(rs.getString("dose_per_day"));
                            tvElement4.setText(rs.getString("description"));
                            tvElement5.setText(rs.getString("rest_date_start"));
                            tvElement6.setText(rs.getString("rest_date_end"));
                            tvElement7.setText(rs.getString("doctor_id"));
                            tvElement8.setText(rs.getString("patient_id"));
                            tvElement9.setText(" Test");
                        } catch (SQLException e) {
                            e.printStackTrace();
                        }

                        tvTitulli9.setVisibility(View.GONE);
                        tvElement9.setVisibility(View.GONE);


                        break;
                    case "hm":
                        tvTitulli1.setText("Semundja : ");
                        tvTitulli2.setText("Simptomat : ");
                        tvTitulli3.setText("Preparati : ");
                        tvTitulli4.setText("Dosa (ne dite) : ");
                        tvTitulli5.setText("Progresi : ");
                        tvTitulli6.setText("Id Doktorit : ");
                        tvTitulli7.setText("Id Pacientit : ");
                        tvTitulli8.setText("Test : ");
                        tvTitulli9.setText("Test : ");


                        try {
                            tvElement1.setText(rs.getString("disease"));
                            tvElement2.setText(rs.getString("symtoms"));
                            tvElement3.setText(rs.getString("medicine"));
                            tvElement4.setText(rs.getString("dose_per_day"));
                            tvElement5.setText(rs.getString("patient_progress"));
                            tvElement6.setText(rs.getString("doctor_id"));
                            tvElement7.setText(rs.getString("patient_id"));
                            tvElement8.setText("Test");
                            tvElement9.setText(" Test");
                        } catch (SQLException e) {
                            e.printStackTrace();
                        }

                        tvTitulli8.setVisibility(View.GONE);
                        tvTitulli9.setVisibility(View.GONE);

                        tvElement8.setVisibility(View.GONE);
                        tvElement9.setVisibility(View.GONE);
                        break;
                    case "il":
                        tvTitulli1.setText("Kodi ATC : ");
                        tvTitulli2.setText("Emertimi Kimik : ");
                        tvTitulli3.setText("Emri Tregtar : ");
                        tvTitulli4.setText("Firma : ");
                        tvTitulli5.setText("Forma : ");
                        tvTitulli6.setText("Cmimi : ");
                        tvTitulli7.setText("Cmim Pacienti : ");
                        tvTitulli8.setText("Rimbursimi : ");
                        tvTitulli9.setText("Semundja : ");


                        try {
                            tvElement1.setText(rs.getString("Kodi_ATC"));
                            tvElement2.setText(rs.getString("Emertimi_Kimik"));
                            tvElement3.setText(rs.getString("Emri_Tregtar"));
                            tvElement4.setText(rs.getString("Firma"));
                            tvElement5.setText(rs.getString("Forma"));
                            tvElement6.setText(rs.getString("Cmimi"));
                            tvElement7.setText(rs.getString("Cmim_Pacienti"));
                            tvElement8.setText(rs.getString("Rimbursimi"));
                            tvElement9.setText(" Test");
                        } catch (SQLException e) {
                            e.printStackTrace();
                        }

                        tvTitulli9.setVisibility(View.GONE);
                        tvElement9.setVisibility(View.GONE);
                        break;
                }

            }
        }
    }

}
