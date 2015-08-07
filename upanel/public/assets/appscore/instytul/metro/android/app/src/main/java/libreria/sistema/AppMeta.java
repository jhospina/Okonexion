package libreria.sistema;

import android.app.Activity;
import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.util.Log;

/**
 * Created by Jhon on 23/07/2015.
 */
public class AppMeta {

    Activity activity;
    private long id;
    private String clave;
    private String valor;
    ControladorBaseDatos dbc;

    public AppMeta(Activity activity) {
        this.activity = activity;
        dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
    }


    /**
     * GUARDA LA INFORMACIÓN META EN LA BASE DE DATOS
     */
    public boolean save() {

        SQLiteDatabase db = this.dbc.getWritableDatabase();
        ContentValues values = new ContentValues();
        values.put("clave", this.clave);
        values.put("valor", this.valor);
        if (this.id > 0) {
            db.update(ControladorBaseDatos.tabla_meta, values, "id=" + this.id, null);
        }
        else {
            this.setId(db.insert(ControladorBaseDatos.tabla_meta, null, values));
        }

        db.close();

        return true;
    }


    public static AppMeta findById(Activity activity, int id) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        Cursor c = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_meta + " where id='" + id + "'", null);

        if (c.moveToFirst()) {
            AppMeta meta = new AppMeta(activity);
            meta.setId(c.getInt(c.getColumnIndex("id")));
            meta.setClave(c.getString(c.getColumnIndex("clave")));
            meta.setValor(c.getString(c.getColumnIndex("valor")));
            db.close();
            return meta;
        }
        db.close();
        return null;
    }


    public static AppMeta findByClave(Activity activity, String clave) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        Cursor c = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_meta + " where clave='" + clave + "'", null);


        if (c.moveToFirst()) {
            AppMeta meta = new AppMeta(activity);
            meta.setId(c.getInt(c.getColumnIndex("id")));
            meta.setClave(c.getString(c.getColumnIndex("clave")));
            meta.setValor(c.getString(c.getColumnIndex("valor")));
            db.close();
            return meta;
        }
        db.close();
        return null;
    }

    public boolean delete(){
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getWritableDatabase();
        db.delete(ControladorBaseDatos.tabla_meta,"id="+this.id,null);
        return true;
    }

    public void setId(long id) {
        this.id = id;
    }

    public void setClave(String clave) {
        this.clave = clave;
    }

    public void setValor(String valor) {
        this.valor = valor;
    }


    public long getId() {
        return id;
    }

    public String getClave() {
        return clave;
    }

    public String getValor() {
        return valor;
    }
}
