package com.example.amdoc1;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.WindowManager;
import android.widget.CalendarView;
import android.widget.Toast;

import androidx.annotation.NonNull;
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
import java.util.ArrayList;


public class Kalendari extends AppCompatActivity {
    private Context ctx=this;
    private CalendarView mCalendarView;

    private ArrayList<String> mDates = new ArrayList<>();
    private ArrayList<String> mPershkrimet = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.calendar_activity);
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
                Intent intent = new Intent(Kalendari.this, Chat.class);
                startActivity(intent);
            }
        });

        // add back arrow to toolbar
        if (getSupportActionBar() != null){
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#000e1a")));
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }


        mCalendarView=(CalendarView) findViewById(R.id.calendarView);

        mCalendarView.setOnDateChangeListener(new CalendarView.OnDateChangeListener() {
            @Override
            public void onSelectedDayChange(@NonNull CalendarView view, int year, int month, int dayOfMonth) {
                clearRecycleView();
                initImageBitmaps(year,month+1,dayOfMonth);

            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.kalendari_menu, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.takime_menu:
                Intent intent = new Intent(getApplicationContext(), Listime.class);
                intent.putExtra("Tipi", "Lista Takimeve");
                startActivity(intent);
                return true;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    @Override
    public boolean onSupportNavigateUp() {
        Intent intent = new Intent(this, Home.class);
        startActivity(intent);
        finish();
        return true;
    }



    private void initImageBitmaps(int year, int month, int dayOfMonth){

        try{
            ResultSet rs = new MerrTakimet().execute(year,month,dayOfMonth).get();

            do {
                mDates.add(rs.getDate("holidat_date").toString());
                mPershkrimet.add(rs.getString("pershkrimi"));
            }while(rs.next());

            if(mDates.size()>0){
                Toast.makeText(getApplicationContext(),"Ju keni "+ mDates.size() +" takime per kte date",Toast.LENGTH_SHORT).show();
            }

        }catch(Exception e){
            e.printStackTrace();
        }

        initRecyclerView();
    }

    private void initRecyclerView(){
        RecyclerView recyclerView = findViewById(R.id.recyclerv_view);
        RecyclerViewAdapter adapter = new RecyclerViewAdapter(this, mDates, mPershkrimet,"lt");
        recyclerView.setAdapter(adapter);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
    }


    private class MerrTakimet extends AsyncTask<Integer, ResultSet, ResultSet> {

        @Override
        protected ResultSet doInBackground(Integer... params) {
            try {

                Integer year = params[0];
                Integer month = params[1];
                Integer dayOfMonth = params[2];

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
                    Integer count=0;

                    String sql = "select * from Calendar "
                            + "where holidat_date = '" + year + "-" + month + "-" + dayOfMonth + "' "
                            + "and patient_Id = " + UserCredentials.PATIENT_ID + "";
                    Statement statement = conn.createStatement();
                    ResultSet rs = statement.executeQuery(sql);

                    while (rs.next()) {
                        count++;
                        break;
                    }

                    if(count>0){
                        return rs;
                    }else{
                        return null;
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }

        protected void onPostExecute(ResultSet rs) {
            if(rs==null){
                Toast.makeText(getApplicationContext(),"Ju nuk keni asnje takim!!",Toast.LENGTH_SHORT).show();
            }
        }
    }

    public void clearRecycleView() {
        mDates.clear();
        mPershkrimet.clear();
    }


}
