package libreria.tipos_contenido;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.Point;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TableRow;
import android.widget.TextView;


import com.appsthergo.instytul.metro.nombreApp.VerInstitucionalActivity;

import libreria.complementos.Util;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;

/**
 * Created by Jhon on 26/03/2015.
 */
public class Institucional extends TipoContenido {

    public Institucional(Activity activity) {
        super(activity);
    }


    /**
     * Busca y obtiene una institucional dada por su numero ID
     *
     * @param activity La actividad actual
     * @param id       El id de la institucional a buscar
     * @return
     */
    public static Institucional buscar(Activity activity, int id) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        Cursor c = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_institucional + " where id='" + id + "'", null);

        if (c.moveToFirst()) {
            Institucional inst = new Institucional(activity);
            inst.setId(c.getInt(0));
            inst.setTitulo(c.getString(2));
            inst.setDescripcion(c.getString(3));
            inst.setFecha(c.getString(4));
            return inst;
        }

        return null;
    }


    /**
     * Carga un listado de informacion institucional en un layout dado por su Referencia
     *
     * @param layout (int) El layout donde se mostrara la informacion institucional
     */
    public void cargar(int layout) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        ViewGroup contenedor = (ViewGroup) activity.findViewById(layout);
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_1)));
        ComponenteInterfaz interfaz=new ComponenteInterfaz(activity);

        Cursor inst = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_institucional + " ORDER BY id ASC", null);

        if (inst.moveToFirst()) {

            int n = 0;
            TableRow fila = null;
            Point pantalla = Util.obtenerTamanoPantalla(activity);
            int ancho_cont = Math.round(pantalla.x / 2);
            int altura_cont = Math.round((App.H_IMAGEN_NOTICIA * ancho_cont) / App.W_IMAGEN_NOTICIA);

            do {

                n++;
                App.institucional_cargadas++;
                final int id_institucional = inst.getInt(0);
                String titulo = inst.getString(2);

                //TITULO DE LA institucional
                TextView txt_titulo = new TextView(activity);
                txt_titulo.setText(titulo);
                txt_titulo.setTextSize(18);
                txt_titulo.setTypeface(null,Typeface.BOLD);
                txt_titulo.setTextColor(Color.parseColor(App.colorNombreApp));
                txt_titulo.setTextAlignment(View.TEXT_ALIGNMENT_CENTER);
                txt_titulo.setGravity(Gravity.CENTER);


                if (n % 2 != 0) {
                    fila = new TableRow(activity);
                    fila.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_1)));
                }

                LinearLayout lay_institucional = interfaz.crear_LinearLayout(App.colorFondoMenuBt_3,LinearLayout.VERTICAL);
                lay_institucional.setGravity(Gravity.BOTTOM);

                lay_institucional.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent ver_inst = new Intent(activity, VerInstitucionalActivity.class);
                        ver_inst.putExtra("id_institucional", id_institucional);
                        activity.startActivity(ver_inst);
                    }
                });


                LinearLayout lay_titulo = interfaz.crear_LinearLayout(Util.oscurecerColor(App.colorBarraApp, 40),LinearLayout.VERTICAL);
                lay_titulo.setGravity(Gravity.BOTTOM);

                LinearLayout.LayoutParams pm = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
                pm.setMargins(3, 3, 3, 3);
                lay_titulo.addView(txt_titulo, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
                lay_titulo.setPadding(5, 5, 5, 5);

                lay_institucional.addView(lay_titulo, pm);

                //Posiciona a dos columnas el ultimo elemento
                if (inst.getCount() % 2 != 0 && inst.isLast()) {
                    TableRow.LayoutParams params = new TableRow.LayoutParams();
                    params.span = (n > 1) ? 2 : 1;
                    params.width=pantalla.x;
                    params.height= (int) (altura_cont/1.5);
                   fila.addView(lay_institucional, params);
                }
                else {
                    fila.addView(lay_institucional, ancho_cont, altura_cont);
                }

                if (n % 2 != 0)
                    contenedor.addView(fila, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);


            } while (inst.moveToNext());

        } else {

            if (activity.findViewById(App.id_mensaje_no_hay_contenido) == null) {
                LinearLayout msj = interfaz.crear_mensajeLogo(App.txt_info_no_hay_contenido_vuelve_mas_tarde, App.txt_menuBtn_3_color);
                msj.setId(App.id_mensaje_no_hay_contenido);
                msj.setGravity(Gravity.CENTER);
                contenedor.addView(msj, interfaz.parentContentAlign(Gravity.CENTER));
            }
        }


        db.close();
    }

}