package com.example.amdoc1;

import android.app.DatePickerDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;
import java.sql.Time;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Timer;
import java.util.TimerTask;

public class Listime extends AppCompatActivity {

    private Context ctx=this;
    private String tipi="";

    private Timer timer;

    private ArrayList<String> mkoka = new ArrayList<>();
    private ArrayList<String> mTrupi = new ArrayList<>();
    private ArrayList<Integer> mId = new ArrayList<>();
    private EditText etKerko,etKerkoDate;
    private ImageView icClear;
    private LinearLayout icClearLayout;
    private ResultSet rs;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.listime_layout);

        etKerko=(EditText) findViewById(R.id.etKerko);
        etKerkoDate=(EditText) findViewById(R.id.etKerkoDate);
        icClear=(ImageView) findViewById(R.id.icClear);
        icClearLayout=(LinearLayout) findViewById(R.id.icClearLayout);

        icClearLayout.setVisibility(View.GONE);


        Bundle bundle = getIntent().getExtras();
        if (bundle != null) {
            switch(bundle.getString("Tipi")){
                case "Lista Takimeve":
                    setTitle("Lista Takimeve");
                    etKerko.setHint("Kerko sipas Pershkrimit");
                    etKerkoDate.setVisibility(View.GONE);
                    tipi="lt";
                    break;
                case "Lista Recetave":
                    setTitle("Lista Recetave");
                    etKerko.setHint("Kerko sipas permbajtjes se recetes");
                    etKerkoDate.setFocusable(false);
                    tipi="lr";
                    break;
                case "Historiku":
                    setTitle("Historiku Mjeksor");
                    etKerko.setHint("Kerko sipas semundjes");
                    etKerkoDate.setFocusable(false);
                    tipi="hm";
                    break;
                case "Ilace":
                    setTitle("Ilace te rimbursuara");
                    etKerko.setHint("Kerko sipas Ilacit");
                    etKerkoDate.setVisibility(View.GONE);
                    tipi="il";
                    break;
            }
        }


        if (Build.VERSION.SDK_INT >= 21) {
            getWindow().clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
            getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.DarkBlue));
            /*switch(bundle.getString("Tipi")){
                case "Lista Takimeve":
                    getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.KalendariColor));
                    tipi="lt";
                    break;
                case "Lista Recetave":
                    getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.DarkBlue));
                    tipi="lr";
                    break;
                case "Historiku":
                    getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.HistorikuColor));
                    tipi="hm";
                    break;
                case "Ilace":
                    getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.colorPrimaryDark));
                    tipi="il";
                    break;
            }*/

        }
        // toolbar
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        FloatingActionButton fab = findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Listime.this, Chat.class);
                startActivity(intent);
            }
        });

        // add back arrow to toolbar
        if (getSupportActionBar() != null){
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#000e1a")));
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }

        new MerrNgaServer().execute("");

        etKerko.addTextChangedListener(new TextWatcher() {

            private Timer timer=new Timer();
            private final long DELAY = 700; // milliseconds
            @Override
            public void afterTextChanged(Editable s) {
                timer.cancel();
                timer = new Timer();
                timer.schedule(
                        new TimerTask() {
                            @Override
                            public void run() {
                                Listime.this.runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        new MerrNgaServer().execute(etKerko.getText().toString());
                                    }
                                });

                            }
                        },
                        DELAY
                );

            }

            @Override
            public void beforeTextChanged(CharSequence s, int start,
                                          int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start,
                                      int before, int count) {

            }
        });

        etKerkoDate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                DatePickerDialog.OnDateSetListener dpd = new DatePickerDialog.OnDateSetListener() {
                    @Override
                    public void onDateSet(DatePicker view, int year, int monthOfYear,
                                          int dayOfMonth) {

                        int s=monthOfYear+1;
                        String a = dayOfMonth+"/"+s+"/"+year;
                        etKerkoDate.setText(a);
                        icClearLayout.setVisibility(View.VISIBLE);
                        new MerrNgaServer().execute(year+"-"+s+"-"+dayOfMonth);
                    }
                };

                Calendar c = Calendar.getInstance();
                DatePickerDialog d = new DatePickerDialog(Listime.this, dpd,  c.get(Calendar.YEAR),c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH));
                d.show();

            }
        });


        icClear.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                etKerkoDate.setText("Zgjidh Daten");
                icClearLayout.setVisibility(View.GONE);
                new MerrNgaServer().execute("");
            }
        });
    }

    @Override
    public boolean onSupportNavigateUp() {
        Intent intent;
        if(tipi.equals("lt")){
                intent = new Intent(this, Kalendari.class);
        }else{
            intent = new Intent(this, Home.class);
        }
        startActivity(intent);
        finish();
        return true;
    }

    private void initImageBitmaps(){
        try{
            do {
                switch(tipi){
                    case "lt":
                        mkoka.add(rs.getDate("holidat_date").toString());
                        mTrupi.add(rs.getString("pershkrimi"));
                        break;
                    case "lr":
                        mId.add(rs.getInt("Id"));
                        mkoka.add(rs.getDate("date_created").toString());
                        mTrupi.add(rs.getString("description"));
                        break;
                    case "hm":
                        mId.add(rs.getInt("Id"));
                        mkoka.add(rs.getString("disease"));
                        mTrupi.add(rs.getString("patient_progress"));
                        break;
                    case "il":
                        mId.add(rs.getInt("Id"));
                        mkoka.add(rs.getString("Emri_Tregtar"));
                        mTrupi.add(rs.getString("Cmim_Pacienti"));
                        break;
                }
            }while(rs.next());

        }catch(Exception e){
            e.printStackTrace();
        }

        initRecyclerView();
    }

    private void initRecyclerView(){
        RecyclerView recyclerView = findViewById(R.id.recyclerv_view);
        RecyclerViewAdapter adapter = new RecyclerViewAdapter(this, mkoka, mTrupi,mId,tipi);
        recyclerView.setAdapter(adapter);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
    }


    private class MerrNgaServer extends AsyncTask<String, Void, Void> {
        private Integer count=0;
        private String sqlWhere="";
        @Override
        protected Void doInBackground(String... params) {
            try {

                String kerkimiParam=params[0].toUpperCase();

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
                        case "lt":
                             sql = "select * from Calendar where patient_Id = " + UserCredentials.PATIENT_ID + "";

                            if(!kerkimiParam.equals("")){

                                for (String word : kerkimiParam.split(" ")){
                                    sqlWhere +=" and (UPPER(pershkrimi) like '" + word + "%' or UPPER(pershkrimi) like '% " + word + "%')";
                                }
                                sql=sql+sqlWhere;
                            }
                            break;
                        case "lr":
                            sql = "select * from Perscription where patient_Id = " + UserCredentials.PATIENT_ID + "";

                            if(!kerkimiParam.equals("")){
                                if(kerkimiParam.contains("-")){
                                    sqlWhere =" and date_created ='"+kerkimiParam+"'";
                                }else{
                                    for (String word : kerkimiParam.split(" ")){
                                        sqlWhere +=" and (UPPER(description) like '" + word + "%' or UPPER(description) like '% " + word + "%')";
                                    }
                                }

                                sql=sql+sqlWhere;
                            }
                            break;
                        case "hm":
                             sql = "select * from Patient_medical_chart where patient_Id = " + UserCredentials.PATIENT_ID + "";

                            if(!kerkimiParam.equals("")){
                                if(kerkimiParam.contains("-")){
                                    sqlWhere =" and date_created ='"+kerkimiParam+"'";
                                }else{
                                    for (String word : kerkimiParam.split(" ")){
                                        sqlWhere +=" and (UPPER(disease) like '" + word + "%' or UPPER(disease) like '% " + word + "%')";
                                    }
                                }

                                sql=sql+sqlWhere;
                            }
                            break;
                        case "il":
                            sql = "select * from Ilace";

                            if(!kerkimiParam.equals("")){
                                for (String word : kerkimiParam.split(" ")){
                                    sqlWhere +=" where (UPPER(Emri_Tregtar) like '" + word + "%' or UPPER(Emri_Tregtar) like '% " + word + "%')";
                                }
                                sql=sql+sqlWhere;
                            }
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
            if(count <= 0){
                switch(tipi){
                    case "lt":
                        Toast.makeText(getApplicationContext(),"Ju nuk keni asnje takim!!",Toast.LENGTH_SHORT).show();
                        break;
                    case "lr":
                        Toast.makeText(getApplicationContext(),"Ju nuk keni asnje recete!",Toast.LENGTH_SHORT).show();
                        break;
                    case "hm":
                        Toast.makeText(getApplicationContext(),"Ju nuk keni asnje semundje te regjistruar!",Toast.LENGTH_SHORT).show();
                        break;
                    case "il":
                        Toast.makeText(getApplicationContext(),"Nuk ka ilace te rimbursuara !",Toast.LENGTH_SHORT).show();
                        break;
                }

            }else{
                clearRecycleView();
                initImageBitmaps();
            }
        }
    }

    public void clearRecycleView() {
        mkoka.clear();
        mTrupi.clear();
    }
}
