package libreria.tipos_contenido;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.Point;
import android.graphics.drawable.ColorDrawable;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.appsthergo.instytul.metro.appsthergoappname.VerNoticiaActivity;

import libreria.complementos.Util;
import libreria.conexion.CargarImagen;
import libreria.conexion.Conexion;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;

/**
 * Created by Jhon on 26/03/2015.
 */
public class Noticias extends TipoContenido {

    public String urlImagen;
    public static final String iden = "noticias";

    public Noticias(Activity act) {
        super(act);
    }

    /**
     * Busca y obtiene una noticia dada por su numero ID
     *
     * @param activity La actividad actual
     * @param id       El id de la noticia a buscar
     * @return
     */
    public static Noticias buscar(Activity activity, int id) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        Cursor c = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_noticias + " where id='" + id + "'", null);

        if (c.moveToFirst()) {
            Noticias noticia = new Noticias(activity);
            noticia.setId(c.getInt(0));
            noticia.setTitulo(c.getString(2));
            noticia.setDescripcion(c.getString(3));
            noticia.setUrlImagen(c.getString(4));
            noticia.setFecha(c.getString(5));
            return noticia;
        }

        return null;
    }


    /**
     * Carga un listado de noticias en un layout dado por su Referencia
     *
     * @param layout (int) El layout donde se mostrara las noticias
     */
    public void cargar(int layout) {
        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        ViewGroup contenedor = (ViewGroup) activity.findViewById(layout);
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));

        Cursor noticias = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_noticias + " ORDER BY id DESC LIMIT " + (App.noticias_cargadas) + "," + App.noticias_cantidad_a_cargar, null);

        if (noticias.moveToFirst()) {

            int n = 0;
            TableRow fila = null;
            Point pantalla = Util.obtenerTamanoPantalla(activity);
            //Calcula las dimensiones de la imagen de la noticia
            int ancho_imagen = Math.round(pantalla.x / 2);
            int altura_imagen = Math.round((App.H_IMAGEN_NOTICIA * ancho_imagen) / App.W_IMAGEN_NOTICIA);

            do {

                n++;
                App.noticias_cargadas++;
                final int id_noticia = noticias.getInt(0);
                String titulo = noticias.getString(2);
                String URL_imagen = noticias.getString(4);

                //TITULO DE LA NOTICIA
                TextView txt_titulo = new TextView(activity);
                txt_titulo.setText(titulo);
                txt_titulo.setTextColor(Color.parseColor(App.colorNombreApp));
                txt_titulo.setTextAlignment(View.TEXT_ALIGNMENT_CENTER);
                txt_titulo.setGravity(Gravity.CENTER);


                if (n % 2 != 0) {
                    fila = new TableRow(activity);
                    fila.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));
                }

                LinearLayout lay_noticia = new LinearLayout(activity);
                lay_noticia.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_2)));
                lay_noticia.setOrientation(LinearLayout.VERTICAL);
                lay_noticia.setGravity(Gravity.BOTTOM);

                lay_noticia.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent ver_noticias = new Intent(activity, VerNoticiaActivity.class);
                        ver_noticias.putExtra("id_noticia", id_noticia);
                        activity.startActivity(ver_noticias);
                    }
                });


                LinearLayout lay_titulo = new LinearLayout(activity);

                lay_titulo.setOrientation(LinearLayout.VERTICAL);
                lay_titulo.setGravity(Gravity.BOTTOM);
                lay_titulo.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
                lay_titulo.setAlpha(0.7f);


                //SI la Noticia tiene imagen
                if (URL_imagen.length() > 0 && Conexion.verificar(activity)) {
                    //Carga la imagen de la noticia en el layour contenedor de fondo
                    new CargarImagen(activity, URL_imagen, lay_noticia).execute();

                    lay_titulo.addView(txt_titulo, LinearLayout.LayoutParams.MATCH_PARENT, (int) (altura_imagen / 3.5));
                    lay_noticia.addView(lay_titulo, LinearLayout.LayoutParams.MATCH_PARENT, (int) (altura_imagen / 3.5));
                } else {
                    LinearLayout.LayoutParams pm = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
                    pm.setMargins(10, 10, 10, 10);
                    lay_titulo.addView(txt_titulo, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
                    lay_titulo.setPadding(5, 5, 5, 5);
                    lay_noticia.addView(lay_titulo, pm);
                }

                fila.addView(lay_noticia, ancho_imagen, altura_imagen);

                if (n % 2 != 0)
                    contenedor.addView(fila, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);


            } while (noticias.moveToNext());

        } else {

            if (activity.findViewById(App.id_mensaje_no_hay_contenido) == null) {
                // 1 porque hay un progrebar oculto en contenedor
                if (contenedor.getChildCount() > 1) {
                    TextView txt_mensaje = new TextView(activity);
                    txt_mensaje.setId(App.id_mensaje_no_hay_contenido);
                    txt_mensaje.setText(App.txt_info_no_hay_contenido);
                    txt_mensaje.setTextColor(Color.parseColor(App.txt_menuBtn_2_color));
                    txt_mensaje.setTextAlignment(View.TEXT_ALIGNMENT_CENTER);
                    txt_mensaje.setTextSize(15);
                    txt_mensaje.setPadding(10, 10, 10, 10);
                    txt_mensaje.setGravity(Gravity.CENTER);

                    contenedor.addView(txt_mensaje);
                } else {
                    ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);
                    LinearLayout msj = interfaz.crear_mensajeLogo(App.txt_info_no_hay_contenido_vuelve_mas_tarde, App.txt_menuBtn_3_color);
                    msj.setId(App.id_mensaje_no_hay_contenido);
                    msj.setGravity(Gravity.CENTER);
                    contenedor.addView(msj, interfaz.parentContentAlign(Gravity.CENTER));
                }
            }
        }


        db.close();
    }


    public void setUrlImagen(String urlImagen) {
        this.urlImagen = urlImagen;
    }

}
