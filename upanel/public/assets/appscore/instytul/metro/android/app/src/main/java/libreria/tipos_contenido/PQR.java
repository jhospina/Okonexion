package libreria.tipos_contenido;

import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.Point;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable;
import android.text.InputType;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TableRow;
import android.widget.TextView;

import com.appsthergo.instytul.metro.appsthergoappname.R;
import com.appsthergo.instytul.metro.appsthergoappname.pqr.VerPqrActivity;

import java.util.ArrayList;

import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.complementos.Util;
import libreria.sistema.ControladorBaseDatos;

/**
 * Created by Jhon on 16/04/2015.
 */
public class PQR extends TipoContenido {

    public static final String iden = "pqr";
    public static final String TIPO_PETICION = "peticion";
    public static final String TIPO_QUEJA = "queja";
    public static final String TIPO_RECLAMO = "reclamo";
    public static final String TIPO_SUGERENCIA = "sugerencia";


    public PQR(Activity act) {
        super(act);
    }

    /**
     * Obtiene el nombre de un tipo de pqr
     *
     * @param tipo
     * @return
     */
    public static String obtenerNombreTipo(String tipo) {
        if (tipo.contains(PQR.TIPO_PETICION))
            return App.txt_info_peticion;
        if (tipo.contains(PQR.TIPO_QUEJA))
            return App.txt_info_queja;
        if (tipo.contains(PQR.TIPO_RECLAMO))
            return App.txt_info_reclamo;
        if (tipo.contains(PQR.TIPO_SUGERENCIA))
            return App.txt_info_sugerencia;

        return null;
    }


    public static int obtenerIconoTipo(String tipo, String colorFondo) {

        if (Util.esColorOscuro(colorFondo)) {
            if (tipo.contains(PQR.TIPO_PETICION))
                return R.mipmap.img_peticion_white;
            if (tipo.contains(PQR.TIPO_QUEJA))
                return R.mipmap.img_quejas_white;
            if (tipo.contains(PQR.TIPO_RECLAMO))
                return R.mipmap.img_reclamos_white;
            if (tipo.contains(PQR.TIPO_SUGERENCIA))
                return R.mipmap.img_sugerencias_white;
        } else {
            if (tipo.contains(PQR.TIPO_PETICION))
                return R.mipmap.img_peticion_black;
            if (tipo.contains(PQR.TIPO_QUEJA))
                return R.mipmap.img_quejas_black;
            if (tipo.contains(PQR.TIPO_RECLAMO))
                return R.mipmap.img_reclamos_black;
            if (tipo.contains(PQR.TIPO_SUGERENCIA))
                return R.mipmap.img_sugerencias_black;
        }

        return 0;
    }

    /**
     * Carga en un contenedor el formulario para crear un PQR
     *
     * @param layout
     * @param descripcionHints
     * @return
     */
    public ArrayList cargarFormularioCreacion(int layout, String descripcionHints) {
        ViewGroup lay_principal = (ViewGroup) activity.findViewById(layout);
        lay_principal.removeAllViews();

        ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);

        GradientDrawable back = interfaz.crear_Gradient(GradientDrawable.Orientation.TOP_BOTTOM,App.colorFondoMenuBt_4,App.colorFondoMenuBt_4);
        back.setStroke(3, Color.parseColor(App.colorBarraApp));
        back.setCornerRadius(5);

        EditText nombre = interfaz.crear_EditText(App.txt_info_editText_nombre, InputType.TYPE_TEXT_VARIATION_PERSON_NAME, back);
        nombre.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        EditText email = interfaz.crear_EditText(App.txt_info_editText_email, InputType.TYPE_TEXT_VARIATION_EMAIL_ADDRESS, back);
        email.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        EditText asunto = interfaz.crear_EditText(App.txt_info_editText_asunto, InputType.TYPE_CLASS_TEXT, back);
        asunto.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        EditText descripcion = interfaz.crear_EditText(descripcionHints, InputType.TYPE_TEXT_FLAG_MULTI_LINE, back);
        descripcion.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        descripcion.setLines(5);
        descripcion.setHorizontallyScrolling(false);
        descripcion.setSingleLine(false);
        descripcion.setGravity(Gravity.TOP | Gravity.LEFT);


        Button btn_enviar = interfaz.crear_Button(App.txt_info_boton_enviar, App.txt_menuBtn_4_color, interfaz.crear_Gradient(GradientDrawable.Orientation.TOP_BOTTOM, Util.oscurecerColor(App.colorFondoMenuBt_4, 50), Util.oscurecerColor(App.colorFondoMenuBt_4, 100)));
        btn_enviar.setGravity(Gravity.CENTER);


        lay_principal.addView(nombre, interfaz.parentContent());
        lay_principal.addView(email, interfaz.parentContent());
        lay_principal.addView(asunto, interfaz.parentContent());
        lay_principal.addView(descripcion, interfaz.parentContent());
        lay_principal.addView(btn_enviar, interfaz.contentContentAlign(Gravity.CENTER));

        interfaz.establecerMargenes(nombre, 0, 5, 0, 5);
        interfaz.establecerMargenes(email, 0, 5, 0, 5);
        interfaz.establecerMargenes(descripcion, 0, 5, 0, 5);
        interfaz.establecerMargenes(btn_enviar, 0, 5, 0, 5);

        ArrayList array = new ArrayList<EditText>();
        array.add(nombre);
        array.add(email);
        array.add(asunto);
        array.add(descripcion);
        array.add(btn_enviar);

        return array;
    }


    /** Carga en un layout el listado de PQR
     *
     * @param layout
     * @param tipoPqr
     */
    public void cargarContenido(int layout, String tipoPqr) {

        ControladorBaseDatos dbc = new ControladorBaseDatos(activity, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();

        ViewGroup contenedor = (ViewGroup) activity.findViewById(layout);
        contenedor.removeAllViews();
        contenedor.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
        ComponenteInterfaz interfaz = new ComponenteInterfaz(activity);

        Cursor pqrs = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_pqr + " WHERE tipo='" + tipoPqr + "' and id_padre=0 ORDER BY id DESC ", null);

        if (pqrs.moveToFirst()) {

            int n = 0;
            TableRow fila = null;
            Point pantalla = Util.obtenerTamanoPantalla(activity);
            int ancho_cont = Math.round(pantalla.x / 2);
            int altura_cont = Math.round((App.H_IMAGEN_NOTICIA * ancho_cont) / App.W_IMAGEN_NOTICIA);

            do {

                n++;
                final int id_pqr = pqrs.getInt(1);
                String titulo = pqrs.getString(pqrs.getColumnIndex("asunto"));

                //TITULO DE LA institucional
                TextView txt_titulo = new TextView(activity);
                txt_titulo.setText(titulo);
                txt_titulo.setTextSize(18);
                txt_titulo.setTypeface(null, Typeface.BOLD_ITALIC);
                txt_titulo.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
                txt_titulo.setTextAlignment(View.TEXT_ALIGNMENT_CENTER);
                txt_titulo.setGravity(Gravity.CENTER);


                if (n % 2 != 0) {
                    fila = new TableRow(activity);
                    fila.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
                }

                LinearLayout lay_pqr = interfaz.crear_LinearLayout(App.colorBarraApp, LinearLayout.VERTICAL);
                lay_pqr.setGravity(Gravity.BOTTOM);

                lay_pqr.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent ver_inst = new Intent(activity, VerPqrActivity.class);
                        ver_inst.putExtra("id_pqr", id_pqr);
                        activity.startActivity(ver_inst);
                    }
                });


                LinearLayout lay_titulo = interfaz.crear_LinearLayout(App.colorFondoMenuBt_4, LinearLayout.VERTICAL);
                lay_titulo.setGravity(Gravity.BOTTOM);

                LinearLayout.LayoutParams pm = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
                pm.setMargins(3, 3, 3, 3);
                lay_titulo.addView(txt_titulo, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
                lay_titulo.setPadding(5, 5, 5, 5);

                lay_pqr.addView(lay_titulo, pm);

                //Posiciona a dos columnas el ultimo elemento
                if (pqrs.getCount() % 2 != 0 && pqrs.isLast()) {
                    TableRow.LayoutParams params = new TableRow.LayoutParams();
                    params.span = 2;
                    params.width = pantalla.x;
                    params.height = (int) (altura_cont / 1.5);
                    fila.addView(lay_pqr, params);
                } else {
                    fila.addView(lay_pqr, ancho_cont, altura_cont);
                }

                if (n % 2 != 0)
                    contenedor.addView(fila, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);


            } while (pqrs.moveToNext());
        }

    }


    /** Retorna un layout como interfaz en donde se muestra el mensaje del usuario del pqr
     *
     * @param usuarioID
     * @param mensaje
     * @return
     */
    public LinearLayout cargarMensaje(String usuarioID, String mensaje, String fecha) {

        ComponenteInterfaz interfaz=new ComponenteInterfaz(activity);
        TextView txt_usuario;

        LinearLayout contenedorMensaje = new LinearLayout(activity);
        contenedorMensaje.setOrientation(LinearLayout.VERTICAL);
        if (Util.esNumerico(usuarioID)) {
            contenedorMensaje.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
            txt_usuario=interfaz.crear_TextView(App.txt_info_usuario_soporte+" "+App.nombreApp+":",App.txt_menuBtn_4_color,15,Typeface.BOLD);
        }
        else {
            txt_usuario=interfaz.crear_TextView(App.txt_info_usuario+":",App.txt_menuBtn_4_color,15,Typeface.BOLD);
            contenedorMensaje.setBackground(new ColorDrawable(Color.parseColor(App.txt_menuBtn_4_color)));
        }
        contenedorMensaje.setPadding(10, 10, 10, 10);

        TextView txt_mensaje = new TextView(activity);
        txt_mensaje.setText(mensaje);
        txt_mensaje.setTextSize(15);
        txt_mensaje.setPadding(10, 10, 10, 10);
        txt_mensaje.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
        txt_mensaje.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));

        txt_usuario.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));
        txt_usuario.setPadding(5,5,5,10);
        contenedorMensaje.addView(txt_usuario, interfaz.parentContent());
        contenedorMensaje.addView(txt_mensaje, interfaz.parentContent());

        TextView txt_fecha=interfaz.crear_TextView(fecha,App.txt_menuBtn_4_color,12,Typeface.ITALIC);
        txt_fecha.setPadding(5,5,5,5);
        txt_fecha.setGravity(Gravity.RIGHT);
        txt_fecha.setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));

        contenedorMensaje.addView(txt_fecha, interfaz.parentContent());
        return contenedorMensaje;
    }

    /** Carga en un layout toda la discusión de mensajes de un PQR entre el usuario y el soporte
     *
     * @param db
     * @param id_pqr
     * @return
     */
    public LinearLayout cargarDiscusionPqr(SQLiteDatabase db, int id_pqr) {

        LinearLayout layout=new LinearLayout(activity);
        layout.setOrientation(LinearLayout.VERTICAL);

        if (db != null) {
            //Consulta los mensajes del Pqr
            Cursor pqr = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_pqr + " WHERE id_padre=" + id_pqr+" ORDER BY id ASC", null);
                if(pqr.moveToFirst())
                {
                    //carga los mensajes y lo agrega al layout
                    do{
                        String usuario=pqr.getString(pqr.getColumnIndex("usuario"));
                        String mensaje=pqr.getString(pqr.getColumnIndex("descripcion"));
                        String fecha=pqr.getString(pqr.getColumnIndex("fecha"));
                        layout.addView(cargarMensaje(usuario,mensaje,fecha),new LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));
                    }while (pqr.moveToNext());
                }

        }

        return layout;
    }

}
