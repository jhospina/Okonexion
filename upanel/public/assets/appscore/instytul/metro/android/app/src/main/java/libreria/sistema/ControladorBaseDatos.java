package libreria.sistema;

/**
 * Created by Jhon on 24/03/2015.
 */

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteDatabase.CursorFactory;
import android.database.sqlite.SQLiteOpenHelper;

public class ControladorBaseDatos extends SQLiteOpenHelper {

    public static String nombreDB="db_okonexion22";
    public static String tabla_noticias="noticias";
    public static String tabla_institucional="institucional";
    public static String tabla_encuestas="encuestas";
    public static String tabla_encuestas_respuestas="encuestas_respuestas";
    public static String tabla_pqr="pqr";


    String tablaNoticias ="CREATE TABLE "+ControladorBaseDatos.tabla_noticias+" (id INTEGER PRIMARY KEY AUTOINCREMENT, id_noticia INT, titulo TEXT, descripcion TEXT, imagen TEXT,fecha TEXT)";
    String tablaInstitucional="CREATE TABLE "+ControladorBaseDatos.tabla_institucional+" (id INTEGER PRIMARY KEY AUTOINCREMENT, id_institucional INT, titulo TEXT, descripcion TEXT,fecha TEXT)";
    String tablaEncuestas="CREATE TABLE "+ControladorBaseDatos.tabla_encuestas+" (id INTEGER PRIMARY KEY AUTOINCREMENT, id_encuesta INT, titulo TEXT, descripcion TEXT,fecha TEXT, estado TEXT)";
    String tablaEncuestasRespuestas="CREATE TABLE "+ControladorBaseDatos.tabla_encuestas_respuestas+" (id INTEGER PRIMARY KEY AUTOINCREMENT, id_respuesta INT, id_encuesta INT, nombre TEXT, total INT, estado NUMERIC(1) NOT NULL)";
    String tablaPqr="CREATE TABLE "+ControladorBaseDatos.tabla_pqr+" (id INTEGER PRIMARY KEY AUTOINCREMENT, id_pqr INT, id_padre INT, usuario TEXT, nombre TEXT, email TEXT, asunto TEXT, descripcion TEXT, tipo TEXT, fecha TEXT)";


    public ControladorBaseDatos(Context contexto, String nombre, CursorFactory factory, int version) {
        super(contexto, nombre, factory, version);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        db.execSQL(tablaNoticias);
        db.execSQL(tablaInstitucional);
        db.execSQL(tablaEncuestas);
        db.execSQL(tablaEncuestasRespuestas);
        db.execSQL(tablaPqr);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int versionAnterior, int versionNueva) {
        //Se elimina la versión anterior de la tabla
        db.execSQL("DROP TABLE IF EXISTS "+ControladorBaseDatos.tabla_noticias);
        db.execSQL(tablaNoticias);
        db.execSQL("DROP TABLE IF EXISTS "+ControladorBaseDatos.tabla_institucional);
        db.execSQL(tablaInstitucional);
        db.execSQL("DROP TABLE IF EXISTS "+ControladorBaseDatos.tabla_encuestas);
        db.execSQL(tablaEncuestas);
        db.execSQL("DROP TABLE IF EXISTS "+ControladorBaseDatos.tabla_encuestas_respuestas);
        db.execSQL(tablaEncuestasRespuestas);
        db.execSQL("DROP TABLE IF EXISTS "+ControladorBaseDatos.tabla_pqr);
        db.execSQL(tablaPqr);
    }


    public static boolean existeRegistro(SQLiteDatabase db, String NombreTabla, String columna, String valor){
        //Si hemos abierto correctamente la base de datos
            Cursor c = db.rawQuery("SELECT * FROM "+NombreTabla+" WHERE "+columna+"='"+valor+"'", null);
            //Nos aseguramos de que existe al menos un registro
            if (c.moveToFirst())
                return true;
            else
                return false;
    }

    public static void vaciarTabla(SQLiteDatabase db, String NombreTabla){
        db.execSQL("DELETE FROM "+NombreTabla);
    }


}
