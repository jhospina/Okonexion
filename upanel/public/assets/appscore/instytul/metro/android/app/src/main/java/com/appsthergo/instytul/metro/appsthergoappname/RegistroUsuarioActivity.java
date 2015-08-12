package com.appsthergo.instytul.metro.appsthergoappname;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.TextView;

import java.util.ArrayList;

import libreria.complementos.Util;
import libreria.conexion.Conexion;
import libreria.extensiones.ComponenteInterfaz;
import libreria.sistema.App;
import libreria.sistema.AppConfig;
import libreria.sistema.AppMeta;


public class RegistroUsuarioActivity extends ActionBarActivity {

    String resp_genero = null;
    String resp_edad=null;
    String resp_email=null;
    ArrayList<String> resp_aficiones=new ArrayList<>();
    static String reg_genero="genero";
    static String reg_edad="edad";
    static String reg_email="email";
    static String reg_aficiones="aficiones";
    static boolean registro=false;
    static String reg_omitir="omitir";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_registro_usuario);

        if(!App.flag_editar_perfil) {

            if (AppMeta.findByClave(RegistroUsuarioActivity.this, reg_omitir) != null) {
                Intent intent = new Intent(RegistroUsuarioActivity.this, MenuPrincipal.class);
                startActivity(intent);
            }

        }else{
            ((LinearLayout) findViewById(R.id.layContent_msj_inicial)).setVisibility(View.GONE);
            ((LinearLayout) findViewById(R.id.layContent_pregunta1)).setVisibility(View.VISIBLE);
        }




        this.getSupportActionBar().hide();

        establecerTextos();

        final LinearLayout layInicial = (LinearLayout) findViewById(R.id.layContent_msj_inicial);
        final LinearLayout layPregunta1 = (LinearLayout) findViewById(R.id.layContent_pregunta1);
        final LinearLayout layPregunta2 = (LinearLayout) findViewById(R.id.layContent_pregunta2);
        final LinearLayout layPregunta3 = (LinearLayout) findViewById(R.id.layContent_pregunta3);
        final LinearLayout layPregunta4 = (LinearLayout) findViewById(R.id.layContent_pregunta4);
        final LinearLayout layFinal = (LinearLayout) findViewById(R.id.layContent_reg_fin);


        Button btn_reg_si=(Button)findViewById(R.id.Btn_reg_si);
        Button btn_reg_mas_tarde=(Button)findViewById(R.id.Btn_reg_mas_tarde);


        btn_reg_si.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                layInicial.setVisibility(View.GONE);
                layPregunta1.setVisibility(View.VISIBLE);
            }
        });

        btn_reg_mas_tarde.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AppMeta meta=new AppMeta(RegistroUsuarioActivity.this);
                meta.setClave(reg_omitir);
                meta.setValor(Util.obtenerFechaActual());
                meta.save();

                Intent intent=new Intent(RegistroUsuarioActivity.this,MenuPrincipal.class);
                startActivity(intent);
            }
        });


        Button btn_pregunta1_op_hombre = (Button) findViewById(R.id.Btn_Hombre);
        btn_pregunta1_op_hombre.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                resp_genero = AppConfig.txt_info_reg_pregunta1_resp_op1;
                layPregunta1.setVisibility(View.GONE);
                layPregunta2.setVisibility(View.VISIBLE);
            }
        });

        Button btn_pregunta1_op_mujer = (Button) findViewById(R.id.Btn_Mujer);
        btn_pregunta1_op_mujer.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                resp_genero = AppConfig.txt_info_reg_pregunta1_resp_op2;
                layPregunta1.setVisibility(View.GONE);
                layPregunta2.setVisibility(View.VISIBLE);
            }
        });


        LinearLayout layEdades = (LinearLayout) findViewById(R.id.layContent_pregunta2_edades);
        ComponenteInterfaz interfaz = new ComponenteInterfaz(this);
        int n = 1;
        for (int i = 8; i <= 70; i++) {
            final Button btnEdad = new Button(this);
            btnEdad.setText(String.valueOf(i));
            btnEdad.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {

                    registro=true;
                    resp_edad=btnEdad.getText().toString();
                    layPregunta2.setVisibility(View.GONE);
                    layPregunta3.setVisibility(View.VISIBLE);


                }
            });
            layEdades.addView(btnEdad);
        }

        Button btn_guardar=(Button)findViewById(R.id.Btn_reg_guardar);
        Button btn_omitir=(Button)findViewById(R.id.Btn_reg_omitir);

        btn_guardar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                resp_email=((EditText)findViewById(R.id.input_reg_email)).getText().toString();
                layPregunta3.setVisibility(View.GONE);
                layPregunta4.setVisibility(View.VISIBLE);
            }
        });

        btn_omitir.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                layPregunta3.setVisibility(View.GONE);
                layPregunta4.setVisibility(View.VISIBLE);
            }
        });


        Button btn_guardar_aficiones=(Button)findViewById(R.id.Btn_reg_aficiones_guardar);
        btn_guardar_aficiones.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                layPregunta4.setVisibility(View.GONE);
                layFinal.setVisibility(View.VISIBLE);


                String[] aficiones= (AppConfig.txt_info_reg_aficiones).split(",");
                for(int i=0;i<aficiones.length;i++){
                    AppMeta metaAficion=AppMeta.findByClave(RegistroUsuarioActivity.this,App.obtenerIdDispositivo(RegistroUsuarioActivity.this)+"_"+RegistroUsuarioActivity.reg_aficiones+"_"+Util.adecuarTextoParaRef(aficiones[i]));
                    if(metaAficion!=null)
                        metaAficion.delete();
                }

                Thread hilo = new Thread(new Runnable() {
                    @Override
                    public void run() {

                        if(!App.flag_editar_perfil) {
                            AppMeta meta = new AppMeta(RegistroUsuarioActivity.this);
                            meta.setClave(reg_omitir);
                            meta.setValor(Util.obtenerFechaActual());
                            meta.save();
                        }

                        String[][] datos=new String[3+resp_aficiones.size()][2];

                        datos[0][0]= App.obtenerIdDispositivo(RegistroUsuarioActivity.this)+"_"+reg_genero;
                        datos[0][1]= resp_genero;
                        datos[1][0]= App.obtenerIdDispositivo(RegistroUsuarioActivity.this)+"_"+reg_edad;
                        datos[1][1]= resp_edad;
                        if(resp_email!=null) {
                            datos[2][0] = App.obtenerIdDispositivo(RegistroUsuarioActivity.this) + "_" + reg_email;
                            datos[2][1] = resp_email;
                        }

                        for(int i=0;i<resp_aficiones.size();i++){
                            datos[3+i][0]= App.obtenerIdDispositivo(RegistroUsuarioActivity.this)+"_"+RegistroUsuarioActivity.reg_aficiones+"_"+Util.adecuarTextoParaRef(resp_aficiones.get(i));
                            datos[3+i][1]= resp_aficiones.get(i);
                        }

                        Conexion.registrarMetaDato(RegistroUsuarioActivity.this,datos);
                    }
                });
                hilo.start();

            }
        });


        Button btn_continuar=(Button)findViewById(R.id.Btn_reg_continuar);
        btn_continuar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                guardarPerfil();
            }
        });

    }


    private void establecerTextos() {
        ((TextView) findViewById(R.id.txt_msj_inicial)).setText(AppConfig.txt_info_reg_msj_inicial);
        ((TextView) findViewById(R.id.txt_pregunta_registrar_datos)).setText(AppConfig.txt_info_reg_pregunta_registro);
        ((TextView) findViewById(R.id.txt_pregunta1)).setText(AppConfig.txt_info_reg_pregunta1);
        ((Button) findViewById(R.id.Btn_Hombre)).setText(AppConfig.txt_info_reg_pregunta1_resp_op1);
        ((Button) findViewById(R.id.Btn_Mujer)).setText(AppConfig.txt_info_reg_pregunta1_resp_op2);
        ((TextView) findViewById(R.id.txt_pregunta2)).setText(AppConfig.txt_info_reg_pregunta2);
        ((TextView) findViewById(R.id.txt_pregunta3)).setText(AppConfig.txt_info_reg_pregunta3);
        ((EditText) findViewById(R.id.input_reg_email)).setHint(AppConfig.txt_info_placeholder_escribir_aqui);
        ((Button) findViewById(R.id.Btn_reg_guardar)).setText(AppConfig.txt_info_guardar);
        ((Button) findViewById(R.id.Btn_reg_omitir)).setText(AppConfig.txt_info_omitir);

        ((TextView) findViewById(R.id.txt_reg_fin)).setText((App.flag_editar_perfil)?AppConfig.txt_info_msj_editar_perfil:AppConfig.txt_info_reg_fin);
        ((Button) findViewById(R.id.Btn_reg_continuar)).setText(AppConfig.txt_info_continuar);
        ((Button) findViewById(R.id.Btn_reg_si)).setText(AppConfig.txt_info_si);
        ((Button) findViewById(R.id.Btn_reg_mas_tarde)).setText(AppConfig.txt_info_mas_tarde);
        ((Button) findViewById(R.id.Btn_reg_aficiones_guardar)).setText(AppConfig.txt_info_guardar);

        //Aficiones
        String[] aficiones= (AppConfig.txt_info_reg_aficiones).split(",");
        for(int i=0;i<aficiones.length;i++){
            final CheckBox aficion=new CheckBox(this);
            aficion.setText(aficiones[i]);
            aficion.setTextSize(25);

            aficion.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                    if(isChecked)
                        resp_aficiones.add(Util.adecuarTextoParaRef(aficion.getText().toString()));
                    else
                        resp_aficiones.remove(resp_aficiones.indexOf(Util.adecuarTextoParaRef(aficion.getText().toString())));
                }
            });

            ((LinearLayout) findViewById(R.id.layContent_aficiones_pregunta4)).addView(aficion, LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
        }


    }


    private void guardarPerfil(){


        if(App.flag_editar_perfil) {
            App.flag_editar_perfil=false;
            Intent intent=new Intent(RegistroUsuarioActivity.this,MisDatosActivity.class);
            startActivity(intent);
        }else {
            Intent intent = new Intent(RegistroUsuarioActivity.this, MenuPrincipal.class);
            startActivity(intent);
        }
    }


}
