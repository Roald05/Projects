package com.example.amdoc1;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;


import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;

/**
 * Created by User on 1/1/2018.
 */

public class RecyclerViewAdapter extends RecyclerView.Adapter<RecyclerViewAdapter.ViewHolder>{

    private static final String TAG = "RecyclerViewAdapter";

    private ArrayList<String> mDates = new ArrayList<>();
    private ArrayList<String> mPershkirmet = new ArrayList<>();
    private ArrayList<Integer> mId = new ArrayList<>();
    private Context mContext;
    private String tipi;

    public RecyclerViewAdapter(Context context, ArrayList<String> Dates, ArrayList<String> Pershkirmet , ArrayList<Integer> Id,String Tipi) {
        mDates = Dates;
        mPershkirmet = Pershkirmet;
        mId=Id;
        mContext = context;
        tipi=Tipi;
    }

    public RecyclerViewAdapter(Context context, ArrayList<String> Dates, ArrayList<String> Pershkirmet ,String Tipi) {
        mDates = Dates;
        mPershkirmet = Pershkirmet;
        mContext = context;
        tipi=Tipi;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.layout_listitem, parent, false);
        ViewHolder holder = new ViewHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {
        Log.d(TAG, "onBindViewHolder: called.");

        if(!tipi.equals("lt")){
            holder.cdEventi.setTag(mId.get(position));
        }
        holder.tvData.setText(mDates.get(position));
        holder.tvPershkrimi.setText(mPershkirmet.get(position));

        switch(tipi) {
            case "lr":
                holder.icon.setImageResource(R.mipmap.ic_receta);
                holder.rlEventi.setBackgroundColor(Color.parseColor("#D50000"));
                holder.tvData.setTextColor(Color.parseColor("#ffffff"));
                break;
            case "hm":
                holder.icon.setImageResource(R.mipmap.ic_historiku);
                holder.rlEventi.setBackgroundColor(Color.parseColor("#ffffff"));
                break;
            case "il":
                holder.icon.setImageResource(R.mipmap.ic_ilace);
                holder.rlEventi.setBackgroundColor(Color.parseColor("#000e1a"));
                holder.tvData.setTextColor(Color.parseColor("#ffffff"));
                holder.tvPershkrimi.setTextColor(Color.parseColor("#ffffff"));
                break;
        }

        if(!tipi.equals("lt")){
            holder.cdEventi.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent intent = new Intent(mContext, Details.class);
                     intent.putExtra("tipi", tipi);
                     intent.putExtra("id", Integer.parseInt(view.getTag().toString()));
                    mContext.startActivity(intent);
                }
            });
        }
    }

    @Override
    public int getItemCount() {
        return mDates.size();
    }


    public class ViewHolder extends RecyclerView.ViewHolder{

        TextView tvData;
        TextView tvPershkrimi;
        RelativeLayout parentLayout;
        ImageView icon;
        RelativeLayout rlEventi;
        CardView cdEventi;

        public ViewHolder(View itemView) {
            super(itemView);
            tvData = itemView.findViewById(R.id.tvKoka);
            tvPershkrimi = itemView.findViewById(R.id.tvTrupi);
            icon =  itemView.findViewById(R.id.icon_img);
            parentLayout = itemView.findViewById(R.id.parent_layout);
            rlEventi = itemView.findViewById(R.id.rlEventi);
            cdEventi = itemView.findViewById(R.id.cdEventi);
        }
    }
}















