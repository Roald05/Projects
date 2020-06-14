package com.example.amdoc1;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;

import android.view.MenuItem;
import android.view.View;

import androidx.annotation.NonNull;
import androidx.cardview.widget.CardView;
import androidx.core.content.ContextCompat;
import androidx.core.view.GravityCompat;
import androidx.navigation.NavController;
import androidx.navigation.Navigation;
import androidx.navigation.ui.AppBarConfiguration;
import androidx.navigation.ui.NavigationUI;

import com.google.android.material.navigation.NavigationView;

import androidx.drawerlayout.widget.DrawerLayout;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import android.view.Menu;
import android.view.WindowManager;

public class Home extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener,View.OnClickListener {

    private AppBarConfiguration mAppBarConfiguration;
    private CardView cdReceta,cdKalendari,cdHistoriku,cdIlace;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);



        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        FloatingActionButton fab = findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(Home.this, Chat.class);
                startActivity(intent);
            }
        });

        if (Build.VERSION.SDK_INT >= 21) {
            getWindow().clearFlags(WindowManager.LayoutParams.FLAG_TRANSLUCENT_STATUS);
            getWindow().setStatusBarColor(ContextCompat.getColor(this, R.color.DarkBlue));
        }

        // add back arrow to toolbar
        if (getSupportActionBar() != null){
            getSupportActionBar().setBackgroundDrawable(new ColorDrawable(Color.parseColor("#000e1a")));
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayShowHomeEnabled(true);
        }
        DrawerLayout drawer = findViewById(R.id.drawer_layout);
        NavigationView navigationView = findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
        // Passing each menu ID as a set of Ids because each
        // menu should be considered as top level destinations.
        mAppBarConfiguration = new AppBarConfiguration.Builder(
                R.id.nav_home, R.id.nav_recetaime, R.id.nav_historiku,
                R.id.nav_historiku, R.id.nav_ilace, R.id.nav_share)
                .setDrawerLayout(drawer)
                .build();

        NavController navController = Navigation.findNavController(this, R.id.nav_host_fragment);
        NavigationUI.setupActionBarWithNavController(this, navController, mAppBarConfiguration);
        //NavigationUI.setupWithNavController(navigationView, navController);

        Intent startServiceIntent = new Intent(Home.this, ServiceClass.class);
        startService(startServiceIntent);

        cdReceta = (CardView) findViewById(R.id.cdReceta);
        cdReceta.setOnClickListener(this);
        cdKalendari = (CardView) findViewById(R.id.cdKalendari);
        cdKalendari.setOnClickListener(this);
        cdHistoriku = (CardView) findViewById(R.id.cdHistoriku);
        cdHistoriku.setOnClickListener(this);
        cdIlace = (CardView) findViewById(R.id.cdIlace);
        cdIlace.setOnClickListener(this);
    }

    @Override
    public void onClick(View view) {
        Intent intent;
        switch (view.getId()) {
            case R.id.cdReceta:
                intent = new Intent(getApplicationContext(), Listime.class);
                intent.putExtra("Tipi", "Lista Recetave");
                startActivity(intent);
                break;
            case R.id.cdKalendari:
                intent = new Intent(this, Kalendari.class);
                startActivity(intent);
                finish();
                break;
            case R.id.cdHistoriku:
                intent = new Intent(this, Listime.class);
                intent.putExtra("Tipi", "Historiku");
                startActivity(intent);
                finish();
                break;
            case R.id.cdIlace:
                intent = new Intent(this, Listime.class);
                intent.putExtra("Tipi", "Ilace");
                startActivity(intent);
                finish();
                break;
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.home, menu);
        return true;
    }

     @Override
    public boolean onSupportNavigateUp() {
       NavController navController = Navigation.findNavController(this, R.id.nav_host_fragment);
        return NavigationUI.navigateUp(navController, mAppBarConfiguration)
               || super.onSupportNavigateUp();
        }



    @Override
    public boolean onNavigationItemSelected(MenuItem item) {
        // Handle navigation view item clicks here.
        int id  = item.getItemId();
        item.setChecked(true);
        Intent intent;
        try {
            switch (id){
                case R.id.nav_recetaime:
                    intent = new Intent(getApplicationContext(), Listime.class);
                    intent.putExtra("Tipi", "Lista Recetave");
                    startActivity(intent);
                    break;
                case R.id.nav_kalendari:
                    intent = new Intent(this, Kalendari.class);
                    startActivity(intent);
                    finish();
                    break;
                case R.id.nav_historiku:
                    intent = new Intent(this, Listime.class);
                    intent.putExtra("Tipi", "Historiku");
                    startActivity(intent);
                    finish();
                    break;
                case R.id.nav_ilace:
                    intent = new Intent(this, Listime.class);
                    intent.putExtra("Tipi", "Ilace");
                    startActivity(intent);
                    finish();
                    break;
                case R.id.nav_logout:
                    intent = new Intent(this, MainActivity.class);
                    UserCredentials.PATIENT_ID = 0;
                    UserCredentials.DOCTOR_ID = "";
                    UserCredentials.PATIENT_NAME = "";
                    UserCredentials.PATIENT_SURNAME = "";
                    UserCredentials.ADDRESS = "";
                    UserCredentials.GENDER = "";
                    UserCredentials.PHONE = "";
                    UserCredentials.USERNAME = "";
                    UserCredentials.PASSWORD = "";
                    startActivity(intent);
                    finish();
                    break;

            }
        }catch (Exception e){
            e.printStackTrace();
        }

        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);

        drawer.closeDrawer(GravityCompat.START);
        return true;
    }
}
