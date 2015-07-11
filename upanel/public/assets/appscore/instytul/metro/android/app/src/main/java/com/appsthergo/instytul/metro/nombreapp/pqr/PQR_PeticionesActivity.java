package com.appsthergo.instytul.metro.nombreapp.pqr;

import android.app.ProgressDialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.appsthergo.instytul.metro.nombreApp.R;

import java.util.ArrayList;

import libreria.complementos.Mensaje;
import libreria.complementos.Util;
import libreria.conexion.ComunicacionPQR;
import libreria.conexion.Conexion;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.tipos_contenido.PQR;

public class PQR_PeticionesActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pqr_tipo);

        App.establecerBarraAccion(this, App.txt_menuBtn_4+" - "+App.txt_info_pqr_peticiones);

        apariencia();

        PQR pqr=new PQR(this);
        pqr.cargarContenido(R.id.lay_cargar_contenido_pqr,PQR.TIPO_PETICION);

    }


    public void apariencia(){

        ComponenteInterfaz interfaz=new ComponenteInterfaz(this);

        ScrollView layout=(ScrollView)findViewById(R.id.contenedor_pqr_tipo);
        layout.setBackgroundColor(Color.parseColor(App.colorFondoMenuBt_4));

        //BOTON CREAR PETICION
        final Button btn_crear=(Button) findViewById(R.id.btn_crear_pqr);
        GradientDrawable fondoBtn=interfaz.crear_Gradient(GradientDrawable.Orientation.TOP_BOTTOM, Util.oscurecerColor(App.colorFondoMenuBt_4,50), Util.oscurecerColor(App.colorFondoMenuBt_4,100));
        fondoBtn.setStroke(1, Color.parseColor(App.txt_menuBtn_4_color));
        fondoBtn.setCornerRadius(3);
        btn_crear.setBackground(fondoBtn);
        btn_crear.setText(App.txt_info_btn_crear_peticion);
        btn_crear.setTextColor(Color.parseColor(App.txt_menuBtn_4_color));

        //TITULO "MIS PETICIONES"
        TextView mis_peticiones=(TextView)findViewById(R.id.txt_pqr_tipo);
        mis_peticiones.setText(App.txt_info_mis_peticiones);
        mis_peticiones.setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
        mis_peticiones.setTextColor(Color.parseColor(App.colorNombreApp));


        final PQR pqr=new PQR(this);


        btn_crear.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                btn_crear.setVisibility(View.GONE);
                ((LinearLayout)findViewById(R.id.lay_cont_form_crear_pqr)).setVisibility(View.VISIBLE);

                //Carga el formulario en el contenedor indicado encpantalla
                ArrayList views=pqr.cargarFormularioCreacion(R.id.lay_cont_form_crear_pqr, App.txt_info_editText_descripcion_peticion);
                final EditText nombre=(EditText)views.get(0);
                final EditText email=(EditText)views.get(1);
                final EditText asunto=(EditText)views.get(2);
                final EditText descripcion=(EditText)views.get(3);
                Button btn_enviar=(Button)views.get(4);


                btn_enviar.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {

                        String txt_nombre=nombre.getText().toString();
                        String txt_email=email.getText().toString();
                        String txt_asunto=asunto.getText().toString();
                        String txt_descripcion=descripcion.getText().toString();

                        if(!Conexion.verificar(PQR_PeticionesActivity.this))
                        {
                            Mensaje.alerta(PQR_PeticionesActivity.this, App.txt_info_titulo_sin_conexion, App.txt_info_descripcion_sin_conexion, null);
                            return;
                        }

                        if(txt_nombre.length()<5){
                            Mensaje.alerta(PQR_PeticionesActivity.this,App.txt_info_error,App.txt_info_nombre_error);
                            return;
                        }

                        //Valida el correo electronico
                        if(!Util.validarEmail(txt_email))
                        {
                            Mensaje.alerta(PQR_PeticionesActivity.this,App.txt_info_error,App.txt_info_email_error);
                            return;
                        }

                        if(txt_asunto.length()<5){
                            Mensaje.alerta(PQR_PeticionesActivity.this,App.txt_info_error,App.txt_info_asunto_error);
                            return;
                        }

                        if(txt_descripcion.length()<5){
                            Mensaje.alerta(PQR_PeticionesActivity.this,App.txt_info_error,App.txt_info_descripcion_error);
                            return;
                        }


                        ProgressDialog dialog = new ProgressDialog(PQR_PeticionesActivity.this);
                        dialog.setMessage(App.txt_info_enviando);
                        dialog.show();
                        ComunicacionPQR com = new ComunicacionPQR(PQR_PeticionesActivity.this, dialog, ComunicacionPQR.Dir.enviar,PQR.TIPO_PETICION,R.id.lay_cargar_contenido_pqr);
                        com.execute(txt_nombre,txt_email,txt_asunto,txt_descripcion,"0");

                        btn_crear.setVisibility(View.VISIBLE);
                        ((LinearLayout)findViewById(R.id.lay_cont_form_crear_pqr)).setVisibility(View.GONE);
                    }
                });




            }
        });



    }


}
