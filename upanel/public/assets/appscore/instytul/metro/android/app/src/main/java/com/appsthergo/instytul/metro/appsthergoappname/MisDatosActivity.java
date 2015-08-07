package com.appsthergo.instytul.metro.appsthergoappname;

import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import libreria.complementos.Util;
import libreria.sistema.App;
import libreria.sistema.AppConfig;
import libreria.sistema.AppMeta;
import libreria.sistema.ControladorBaseDatos;


public class MisDatosActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mis_datos);

        App.establecerBarraAccion(this, AppConfig.txt_info_menu_mis_datos);
        establecerApariencia();
        establecerTextos();

        Button btn_editar=(Button)findViewById(R.id.btn_perfil_editar);
        btn_editar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                App.flag_editar_perfil=true;
                Intent intent=new Intent(MisDatosActivity.this,RegistroUsuarioActivity.class);
                startActivity(intent);
            }
        });
    }

    private void establecerApariencia(){
        ((TextView)findViewById(R.id.perfil_info_genero)).setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
        ((TextView)findViewById(R.id.perfil_info_edad)).setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
        ((TextView)findViewById(R.id.perfil_info_email)).setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
        ((TextView)findViewById(R.id.perfil_info_aficiones)).setBackground(new ColorDrawable(Color.parseColor(App.colorBarraApp)));
        ((TextView)findViewById(R.id.perfil_info_genero)).setTextColor(Color.parseColor(App.colorNombreApp));
        ((TextView)findViewById(R.id.perfil_info_edad)).setTextColor(Color.parseColor(App.colorNombreApp));
        ((TextView)findViewById(R.id.perfil_info_email)).setTextColor(Color.parseColor(App.colorNombreApp));
        ((TextView)findViewById(R.id.perfil_info_aficiones)).setTextColor(Color.parseColor(App.colorNombreApp));
    }

    private void establecerTextos(){
        ((TextView)findViewById(R.id.perfil_info_genero)).setText(AppConfig.txt_info_perfil_info_genero);
        ((TextView)findViewById(R.id.perfil_info_edad)).setText(AppConfig.txt_info_perfil_info_edad);
        ((TextView)findViewById(R.id.perfil_info_email)).setText(AppConfig.txt_info_perfil_info_email);
        ((TextView)findViewById(R.id.perfil_info_aficiones)).setText(AppConfig.txt_info_perfil_info_aficiones);

        AppMeta metaGenero=AppMeta.findByClave(this,App.obtenerIdDispositivo(this)+"_"+RegistroUsuarioActivity.reg_genero);
        AppMeta metaEdad=AppMeta.findByClave(this,App.obtenerIdDispositivo(this)+"_"+RegistroUsuarioActivity.reg_edad);
        AppMeta metaEmail=AppMeta.findByClave(this,App.obtenerIdDispositivo(this)+"_"+RegistroUsuarioActivity.reg_email);

        ((TextView)findViewById(R.id.perfil_genero)).setText(metaGenero.getValor());
        ((TextView)findViewById(R.id.perfil_edad)).setText(metaEdad.getValor());
        ((TextView)findViewById(R.id.perfil_email)).setText((metaEmail != null) ? metaEmail.getValor() : AppConfig.txt_info_sin_definir);
        ((Button)findViewById(R.id.btn_perfil_editar)).setText(AppConfig.txt_info_perfil_btn_editar_informacion);


        String[] aficiones= (AppConfig.txt_info_reg_aficiones).split(",");
        for(int i=0;i<aficiones.length;i++) {
            AppMeta metaAficion=AppMeta.findByClave(this, App.obtenerIdDispositivo(this) + "_" + RegistroUsuarioActivity.reg_aficiones + "_" + Util.adecuarTextoParaRef(aficiones[i]));
            if(metaAficion==null)
                continue;

            TextView txt_aficion=new TextView(this);
            txt_aficion.setText(aficiones[i]);
            txt_aficion.setTextSize(20f);
            txt_aficion.setPadding(5, 5, 5, 5);
            ((LinearLayout)findViewById(R.id.perfil_lay_aficiones)).addView(txt_aficion);
        }



    }


    public void onBackPressed()
    {
        super.onBackPressed();
        Intent intent=new Intent(MisDatosActivity.this,MenuPrincipal.class);
        startActivity(intent);
    }

}
