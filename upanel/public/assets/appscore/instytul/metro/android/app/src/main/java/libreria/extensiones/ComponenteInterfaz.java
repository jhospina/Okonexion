package libreria.extensiones;

import android.app.ActionBar;
import android.app.Activity;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.GradientDrawable;
import android.text.InputType;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.TextView;

import com.appsthergo.instytul.metro.nombreApp.R;

import libreria.sistema.App;

/**
 * Created by Jhon on 02/04/2015.
 */
public class ComponenteInterfaz {

    Activity activity;

    public ComponenteInterfaz(Activity act) {
        this.activity = act;
    }

    /**
     * Crear y retorna un TextView
     *
     * @param texto    (String) Descripción del texto
     * @param colorHex (String) Color del texto en hexadecimal
     * @param tamano   (Int) Tamano del texto
     * @param estilo   (Int) Estilo del texto
     * @return Textview
     */
    public TextView crear_TextView(String texto, String colorHex, int tamano, int estilo) {
        TextView txt = new TextView(activity);
        txt.setText(texto);
        txt.setTextSize(tamano);
        txt.setTextColor(Color.parseColor(colorHex));
        if (estilo > 0)
            txt.setTypeface(null, estilo);
        return txt;
    }

    public TextView crear_TextView(String texto, String colorHex, int tamano) {
        return crear_TextView(texto, colorHex, tamano, 0);
    }


    /**
     * Crea y retorna un RadioButton
     *
     * @param texto    El texto que incluira
     * @param colorHex El color del texto
     * @param tamano
     * @return
     */
    public RadioButton crear_RadioButton(String texto, String colorHex, int tamano) {
        RadioButton radio = new RadioButton(activity);
        radio.setText(texto);
        radio.setTextSize(tamano);
        radio.setTextColor(Color.parseColor(colorHex));
        return radio;
    }

    /** Crear y retorna un Button
     *
     * @param texto
     * @param colorTexto
     * @param colorFondo
     * @return
     */
    public Button crear_Button(String texto, String colorTexto, String colorFondo) {
        Button button = new Button(activity);
        button.setText(texto);
        button.setTextColor(Color.parseColor(colorTexto));
        if (colorFondo != null)
            button.setBackground(new ColorDrawable(Color.parseColor(colorFondo)));
        return button;
    }

    public Button crear_Button(String texto, String colorTexto) {
        return crear_Button(texto, colorTexto,(Drawable)null);
    }

    public Button crear_Button(String texto, String colorTexto, Drawable fondo) {
        Button button = new Button(activity);
        button.setText(texto);
        button.setTextColor(Color.parseColor(colorTexto));
        if(fondo!=null) {
            if (fondo.getClass() == GradientDrawable.class) {
                ((GradientDrawable)fondo).setStroke(1, Color.parseColor(colorTexto));
                ((GradientDrawable)fondo).setCornerRadius(3);
            }
            button.setBackground(fondo);
        }
        return button;
    }


    public LinearLayout crear_LinearLayout(String colorFondo,int orientacion){
        LinearLayout layout=new LinearLayout(activity);
        layout.setBackground(new ColorDrawable(Color.parseColor(colorFondo)));
        layout.setOrientation(orientacion);
        return layout;
    }

    public LinearLayout crear_LinearLayout(GradientDrawable colorFondo,int orientacion){
        LinearLayout layout=new LinearLayout(activity);
        layout.setBackground(colorFondo);
        layout.setOrientation(orientacion);
        return layout;
    }


    public GradientDrawable crear_Gradient(GradientDrawable.Orientation direccion,String color1, String color2){
        GradientDrawable gd = new GradientDrawable(direccion, new int[]{Color.parseColor(color1), Color.parseColor(color2)});
        return gd;
    }


    /** Retorna un Progressbar dentro de un linearLayout con texto si se desea
     *
     * @param texto
     * @param orientacion
     * @return
     */
    public LinearLayout crear_ProgressBarLinearLayout(TextView texto, int orientacion){
        LinearLayout contenedor=new LinearLayout(activity);
        contenedor.setOrientation(orientacion);
        contenedor.setGravity(Gravity.CENTER);

        ProgressBar progressBar=new ProgressBar(activity);
        contenedor.addView(progressBar,contentContent());

        if(texto!=null)
        {
            contenedor.addView(texto,contentContent());
        }

        return contenedor;
    }


    /** Crea y retorna una barra de progreso horizontal
     *
     * @param valorMax
     * @param valorProgreso
     * @return
     */
    public ProgressBar crear_ProgressBarHorizontal(int valorMax,int valorProgreso,String colorFondo){
        ProgressBar progressBar=new ProgressBar(activity,null,android.R.attr.progressBarStyleHorizontal);
        progressBar.getProgressDrawable().setColorFilter(Color.parseColor(colorFondo), PorterDuff.Mode.SRC_IN);
        progressBar.setMax(valorMax);
        progressBar.setProgress(valorProgreso);
        return progressBar;
    }



    public LinearLayout crear_mensajeLogo(String texto,String color){
        LinearLayout layout=new LinearLayout(activity);
        layout.setOrientation(LinearLayout.VERTICAL);
        TextView txt_texto=new TextView(activity);
        txt_texto.setTextSize(18);
        txt_texto.setText(texto);
        txt_texto.setTextColor(Color.parseColor(color));
        txt_texto.setPadding(10,10,10,10);
        txt_texto.setGravity(Gravity.CENTER);
        ImageView logo=new ImageView(activity);
        logo.setImageResource(R.mipmap.ic_launcher);
        logo.setScaleType(ImageView.ScaleType.CENTER);
        layout.setPadding(0,10,0,0);

        layout.addView(logo,parentContentAlign(Gravity.CENTER));
        layout.addView(txt_texto,parentContentAlign(Gravity.CENTER));

        return layout;
    }


    /** Establece margenes al elemento
     *
     * @param v El elemento grafico
     * @param izq El margen a la izquierda
     * @param arr El margen a arriba
     * @param der El margen a la derecha
     * @param aba El margen abajo
     */
    public void establecerMargenes(ViewGroup v,int izq,int arr,int der,int aba){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(v.getLayoutParams().width, v.getLayoutParams().height);
        params.setMargins(izq,arr,der,aba);
        v.setLayoutParams(params);
    }

    public void establecerMargenes(TextView txt,int izq,int arr,int der,int aba){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(txt.getLayoutParams().width,txt.getLayoutParams().height);
        params.setMargins(izq,arr,der,aba);
        txt.setLayoutParams(params);
    }

    public void establecerMargenes(Button btn,int izq,int arr,int der,int aba){
        LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(btn.getLayoutParams().width, btn.getLayoutParams().height);
        params.setMargins(izq,arr,der,aba);
        btn.setLayoutParams(params);
    }

    public EditText crear_EditText(String placeholder,int tipoEntrada,GradientDrawable background){
        EditText edit=new EditText(activity);
        edit.setHint(placeholder);
        edit.setInputType(tipoEntrada);
        edit.setLines(1);
        edit.setPadding(10,5,5,5);
        if(background!=null)
        edit.setBackground(background);
        return edit;
    }


    /** Retorna un LayoutParams ajustado a Match_Parent - (Definir)
     *
     * @param height La altura especifica
     * @return
     */
    public LinearLayout.LayoutParams parentWidth(int height) {
        return new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, height);
    }


    public LinearLayout.LayoutParams parentHeight(int width) {
        return new LinearLayout.LayoutParams(width, LinearLayout.LayoutParams.MATCH_PARENT);
    }

    /**
     * Retorna un LayoutParams ajustado a Match_Parent - Wrap_Content
     *
     * @return
     */
    public LinearLayout.LayoutParams parentContent() {
        return new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
    }

    public LinearLayout.LayoutParams parentContentAlign(int gravity) {
        LinearLayout.LayoutParams params=new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
        params.gravity=gravity;
        return  params;
    }

    public LinearLayout.LayoutParams contentContentAlign(int gravity) {
        LinearLayout.LayoutParams params=new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT);
        params.gravity=gravity;
        return  params;
    }

    public LinearLayout.LayoutParams params(int w,int h){
        LinearLayout.LayoutParams params=new LinearLayout.LayoutParams(w,h);
        return  params;
    }

    /**
     * Retorna un LayoutParams ajustado a Match_Parent - Match_Parent
     *
     * @return
     */
    public LinearLayout.LayoutParams parentParent() {
        return new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT);
    }

    /**
     * Retorna un LayoutParams ajustado a Wrap_Content - Wrap_Content
     *
     * @return
     */
    public LinearLayout.LayoutParams contentContent() {
        return new LinearLayout.LayoutParams(LinearLayout.LayoutParams.WRAP_CONTENT, LinearLayout.LayoutParams.WRAP_CONTENT);
    }


}
