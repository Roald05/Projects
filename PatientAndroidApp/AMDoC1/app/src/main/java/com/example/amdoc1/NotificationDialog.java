package com.example.amdoc1;

import android.content.DialogInterface;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Build;
import android.os.Bundle;
import android.view.View;
import android.content.Context;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.content.ContextCompat;

import com.google.android.material.floatingactionbutton.FloatingActionButton;

public class NotificationDialog extends AppCompatActivity {
    private String pyetjat="",pergjigjat="",pyetja="",pergjigja="";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Bundle extras = getIntent().getExtras();
        String userName;

        if (extras != null) {
            pyetjat = extras.getString("date1");
            pergjigjat = extras.getString("date2");
            pyetja = extras.getString("pyetja");
            pergjigja = extras.getString("message");
            // and get whatever type user account id is
        }

        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle("Shkruaj nje mesazh per doktorin");

        // set the custom layout
        final View customLayout = getLayoutInflater().inflate(R.layout.notificationdialog_layout, null);
        builder.setView(customLayout);
        // add a button
        builder.setPositiveButton("Dergo", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                // send data from the AlertDialog to the Activity
                TextView tvPyejtat = customLayout.findViewById(R.id.tvPyetjat);
                TextView tvPyetja = customLayout.findViewById(R.id.tvPyetja);
                TextView tvPergjigjat = customLayout.findViewById(R.id.tvPergjigjat);
                TextView tvPergjigja = customLayout.findViewById(R.id.tvPergjigja);

                tvPyejtat.setText(tvPyejtat.getText().toString() + " " + pyetjat);
                tvPergjigjat.setText(tvPergjigjat.getText().toString() + " " + pergjigjat);

                tvPyetja.setText(pyetja);
                tvPergjigja.setText(pergjigja);
            }
        });
        // create and show the alert dialog
        AlertDialog dialog = builder.create();
        dialog.getWindow().setBackgroundDrawableResource(R.color.AMDoc_Color);
        dialog.show();

    }
}
