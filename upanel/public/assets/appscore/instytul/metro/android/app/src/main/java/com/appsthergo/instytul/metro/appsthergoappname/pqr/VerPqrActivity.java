package com.appsthergo.instytul.metro.appsthergoappname.pqr;

import android.app.ProgressDialog;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.text.InputType;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.appsthergo.instytul.metro.appsthergoappname.R;

import libreria.complementos.Mensaje;
import libreria.complementos.Util;
import libreria.conexion.ComunicacionPQR;
import libreria.conexion.Conexion;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.sistema.ControladorBaseDatos;
import libreria.tipos_contenido.PQR;

public class VerPqrActivity extends ActionBarActivity {

    int id_pqr = 0;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ver_pqr);

        Bundle datos = getIntent().getExtras();
        id_pqr = datos.getInt("id_pqr");

        if(datos.containsKey("msj")){
            Mensaje.alerta(this,App.txt_info_informacion_enviada,datos.getString("msj"));
        }

        init();
    }


    public void init() {

        TextView txt_asunto = ((TextView) findViewById(R.id.txt_titulo_asunto));
        txt_asunto.setText(App.txt_info_asunto);
        txt_asunto.setTextColor(Color.parseColor(App.colorNombreApp));
        txt_asunto.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));

        TextView txt_nombre = ((TextView) findViewById(R.id.txt_titulo_nombre));
        txt_nombre.setText(App.txt_info_nombre);
        txt_nombre.setTextColor(Color.parseColor(App.colorNombreApp));
        txt_nombre.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));

        TextView txt_email = ((TextView) findViewById(R.id.txt_titulo_email));
        txt_email.setTextColor(Color.parseColor(App.colorNombreApp));
        txt_email.setText(App.txt_info_email);
        txt_email.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));

        ControladorBaseDatos dbc = new ControladorBaseDatos(this, ControladorBaseDatos.nombreDB, null, 1);
        SQLiteDatabase db = dbc.getReadableDatabase();


        LinearLayout contendorMensajes = (LinearLayout) findViewById(R.id.cont_mensajes_pqr);
        ComponenteInterfaz interfaz = new ComponenteInterfaz(this);

        if (db != null) {
            Cursor pqr = db.rawQuery("SELECT * FROM " + ControladorBaseDatos.tabla_pqr + " WHERE id_pqr=" + id_pqr, null);

            if (pqr.moveToFirst()) {

                //Obtiene los datos iniciales del PQR
                final int id_pqr = pqr.getInt(pqr.getColumnIndex("id_pqr"));
                final String str_nombre = pqr.getString(pqr.getColumnIndex("nombre"));
                final String str_email = pqr.getString(pqr.getColumnIndex("email"));
                final String str_asunto = pqr.getString(pqr.getColumnIndex("asunto"));
                String str_usuario = pqr.getString(pqr.getColumnIndex("usuario"));
                String str_descripcion = pqr.getString(pqr.getColumnIndex("descripcion"));
                String str_fecha = pqr.getString(pqr.getColumnIndex("fecha"));
                final String tipo_pqr = pqr.getString(pqr.getColumnIndex("tipo"));

                final TextView nombre = ((TextView) findViewById(R.id.txt_pqr_nombre));
                nombre.setText(str_nombre);
                nombre.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));

                TextView email = ((TextView) findViewById(R.id.txt_pqr_email));
                email.setText(str_email);
                email.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));

                TextView asunto = ((TextView) findViewById(R.id.txt_pqr_asunto));
                asunto.setText(str_asunto);
                asunto.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));


                App.establecerBarraAccion(this, PQR.obtenerNombreTipo(tipo_pqr) + " #" + id_pqr);

                TextView titulo_pqr = (TextView) findViewById(R.id.txt_titulo_pqr);
                titulo_pqr.setText(PQR.obtenerNombreTipo(tipo_pqr) + " #" + id_pqr);
                titulo_pqr.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
                titulo_pqr.setCompoundDrawablesWithIntrinsicBounds(getResources().getDrawable(PQR.obtenerIconoTipo(tipo_pqr, App.colorFondoMenuBt_4)), null, null, null);

                ((ScrollView) findViewById(R.id.scroll_ver_pqr)).setBackground(new ColorDrawable(Color.parseColor(App.colorFondoMenuBt_4)));

                //Carga los mensajes del pqr en pantalla
                PQR pqrObject = new PQR(this);
                contendorMensajes.addView(pqrObject.cargarMensaje(str_usuario, str_descripcion,str_fecha), interfaz.parentContent());
                contendorMensajes.addView(pqrObject.cargarDiscusionPqr(db, id_pqr), interfaz.parentContent());


                LinearLayout contenedorPrincipal = (LinearLayout) findViewById(R.id.contenedor_descr_pqr);

                GradientDrawable back = interfaz.crear_Gradient(GradientDrawable.Orientation.TOP_BOTTOM, App.colorFondoMenuBt_4, App.colorFondoMenuBt_4);
                back.setStroke(3, Color.parseColor(App.colorBarraApp));
                back.setCornerRadius(3);

                //Caja de texto donde el usuario puede contestar y enviar sus mensajes referentes al PQR actual
                final EditText mensaje_respuesta = interfaz.crear_EditText(App.txt_info_escribe_tu_respuesta, InputType.TYPE_TEXT_FLAG_MULTI_LINE, back);
                mensaje_respuesta.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));
                mensaje_respuesta.setLines(5);
                mensaje_respuesta.setHorizontallyScrolling(false);
                mensaje_respuesta.setSingleLine(false);
                mensaje_respuesta.setGravity(Gravity.TOP | Gravity.LEFT);
                mensaje_respuesta.setVisibility(View.GONE);

                contenedorPrincipal.addView(mensaje_respuesta, interfaz.parentContent());

                interfaz.establecerMargenes(mensaje_respuesta, 0, 10, 0, 0);



                //Evita que una sugencia se pueda responder... ya que las sugerencias simplemente se una unica vez.
                if(tipo_pqr.contains(PQR.TIPO_SUGERENCIA))
                    return;


                final Button btn_enviarMensaje = interfaz.crear_Button(App.txt_info_escribir_respuesta, App.txt_menuBtn_4_color, interfaz.crear_Gradient(GradientDrawable.Orientation.TOP_BOTTOM, App.colorFondoMenuBt_4, Util.oscurecerColor(App.colorFondoMenuBt_4, 50)));
                contenedorPrincipal.addView(btn_enviarMensaje, interfaz.parentContent());

                interfaz.establecerMargenes(btn_enviarMensaje, 0, 10, 0, 0);

                btn_enviarMensaje.setOnClickListener(new View.OnClickListener() {
                    //Indica cuando se debe enviar el mensaje del usuario
                    boolean flagBtn = false;
                    @Override
                    public void onClick(View v) {
                        if (!flagBtn) {
                            btn_enviarMensaje.setText(App.txt_info_enviar_respuesta);
                            mensaje_respuesta.setVisibility(View.VISIBLE);
                            mensaje_respuesta.requestFocus(mensaje_respuesta.length());
                            flagBtn=true;
                        } else {

                            /**
                             *  VERIFICA LA CONEXION DE INTERNET Y EL MENSAJE DE USUARIO ANTES DE ENVIARLO AL SERVIDOR
                             */

                            if (!Conexion.verificar(VerPqrActivity.this)) {
                                Mensaje.alerta(VerPqrActivity.this, App.txt_info_titulo_sin_conexion, App.txt_info_descripcion_sin_conexion, null);
                                return;
                            }

                            if (mensaje_respuesta.length() < 5) {
                                Mensaje.alerta(VerPqrActivity.this, App.txt_info_error, App.txt_info_descripcion_error);
                                return;
                            }
                            ProgressDialog dialog = new ProgressDialog(VerPqrActivity.this);
                            dialog.setMessage(App.txt_info_enviando);
                            dialog.show();
                            ComunicacionPQR com = new ComunicacionPQR(VerPqrActivity.this, dialog, ComunicacionPQR.Dir.enviar, tipo_pqr);
                            com.execute(str_nombre, str_email, str_asunto, mensaje_respuesta.getText().toString(), id_pqr + "");
                        }
                    }
                });

            }
        }


    }

}
