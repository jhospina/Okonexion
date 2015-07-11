package libreria.complementos;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.widget.Toast;

import libreria.sistema.App;

/**
 * Created by Jhon on 25/03/2015.
 */
public class Mensaje {

    public enum Icono {info, alert, dialert}

    ;

    public static void alerta(Activity activity, String titulo, String mensaje, Icono icono) {

        final AlertDialog.Builder builder = new AlertDialog.Builder(activity);

        builder.setTitle(titulo).setMessage(mensaje);

        if (icono == null)
            builder.setIcon(activity.getResources().getDrawable(android.R.drawable.ic_dialog_info));
        else {
            switch (icono) {
                case info:
                    builder.setIcon(activity.getResources().getDrawable(android.R.drawable.ic_dialog_info));
                    break;
                case alert:
                    builder.setIcon(activity.getResources().getDrawable(android.R.drawable.ic_dialog_alert));
                    break;
                case dialert:
                    builder.setIcon(activity.getResources().getDrawable(android.R.drawable.ic_dialog_dialer));
                    break;
            }
        }

        builder.setNeutralButton(App.txt_info_btn_aceptar, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.cancel();
            }
        });

        builder.show();
    }

    public static void alerta(Activity activity, String titulo, String mensaje) {
        Mensaje.alerta(activity, titulo, mensaje, null);
    }

    public static void toast(Activity activity, String texto) {
        Toast toast =
                Toast.makeText(activity,
                        texto, Toast.LENGTH_LONG);
        toast.show();
    }

}
