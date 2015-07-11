package libreria.tipos_contenido;

import android.app.Activity;

/**
 * Created by Jhon on 30/03/2015.
 */
public class TipoContenido {
    Activity activity;
    public int id;
    public String titulo;
    public String descripcion;
    public String fecha;

    public TipoContenido(Activity act){
        this.activity=act;
    }

    public void setId(int id) {
        this.id = id;
    }

    public void setTitulo(String titulo) {
        this.titulo = titulo;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public void setFecha(String fecha) {
        this.fecha = fecha;
    }
}
