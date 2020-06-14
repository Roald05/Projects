package com.example.amdoc1;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DatabaseHelper extends SQLiteOpenHelper {

    private static final int DATABASE_VERSION = 1;
    private static final String DATABASE_NAME= "AMDocDB";
    private static final String TABLE_KONFIGURIME = "KONFIGURIME";
    private static final String ID = "ID";
    private static final String IP = "Ip";
    private static final String PORT = "Port";
    private static final String DB_NAME = "Db_Name";
    private static final String DBUSERNAME = "DbUsername";
    private static final String DBPASSWORD = "DbPassword";

    public DatabaseHelper(Context context){
        super(context,DATABASE_NAME,null,DATABASE_VERSION);
    }

    @Override
    public void onCreate(SQLiteDatabase db){
        String CREATE_KONFIGURIME_TABLE = "CREATE TABLE " + TABLE_KONFIGURIME + "("
                + ID + " INTEGER PRIMARY KEY AUTOINCREMENT,"
                + IP + " TEXT,"
                + PORT + " INTEGER,"
                + DB_NAME + " TEXT,"
                + DBUSERNAME + " TEXT,"
                + DBPASSWORD + " TEXT" + ")";

        db.execSQL(CREATE_KONFIGURIME_TABLE);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db,int i,int i1){
        db.execSQL("DROP TABLE IF EXISTS " + TABLE_KONFIGURIME);
        onCreate(db);
    }


    void addServerCredetnials(String Ip,String Port,String Db_Name,String DbUsername,String DbPassword){
        SQLiteDatabase db = this.getWritableDatabase();
        db.delete(TABLE_KONFIGURIME,null,null);
        ContentValues cv = new ContentValues();
        cv.put(IP,Ip);
        cv.put(PORT,Port);
        cv.put(DB_NAME,Db_Name);
        cv.put(DBUSERNAME,DbUsername);
        cv.put(DBPASSWORD,DbPassword);
        db.insert(TABLE_KONFIGURIME,null,cv);
        db.close();
    }


     String getIp(){
        SQLiteDatabase db = this.getReadableDatabase();
        String Ip="";

        Cursor cursor = db.query(TABLE_KONFIGURIME, new String[]{IP}, null, null, null, null, null);

        if (cursor.getCount()==1){
            cursor.moveToFirst();
            do{
                Ip=cursor.getString(0);
            }while(cursor.moveToNext());
        }

        return Ip;
    }

     String getPort(){
        SQLiteDatabase db = this.getReadableDatabase();
        String Port="";

        Cursor cursor = db.query(TABLE_KONFIGURIME, new String[]{PORT}, null, null, null, null, null);

        if (cursor.getCount()==1){
            cursor.moveToFirst();
            do{
                Port=cursor.getString(0);
            }while(cursor.moveToNext());
        }

        return Port;
    }

     String getDbName(){
        SQLiteDatabase db = this.getReadableDatabase();
        String DbName="";

        Cursor cursor = db.query(TABLE_KONFIGURIME, new String[]{DB_NAME}, null, null, null, null, null);

        if (cursor.getCount()==1){
            cursor.moveToFirst();
            do{
                DbName=cursor.getString(0);
            }while(cursor.moveToNext());
        }

        return DbName;
    }

      String getDbusername(){
        SQLiteDatabase db = this.getReadableDatabase();
        String DbUser="";

        Cursor cursor = db.query(TABLE_KONFIGURIME, new String[]{DBUSERNAME}, null, null, null, null, null);

        if (cursor.getCount()==1){
            cursor.moveToFirst();
            do{
                DbUser=cursor.getString(0);
            }while(cursor.moveToNext());
        }

        return DbUser;
    }

     String getDbpassword(){
        SQLiteDatabase db = this.getReadableDatabase();
        String DbPass="";

        Cursor cursor = db.query(TABLE_KONFIGURIME, new String[]{DBPASSWORD}, null, null, null, null, null);

        if (cursor.getCount()==1){
            cursor.moveToFirst();
            do{
                DbPass=cursor.getString(0);
            }while(cursor.moveToNext());
        }

        return DbPass;
    }

    String merrServer() {
        return "jdbc:jtds:sqlserver://" + getIp() +":"+ getPort() + ";" + "databaseName=" + getDbName() + ";user=" + getDbusername() + ";password=" + getDbpassword() + ";";
    }

}
