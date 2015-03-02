<?php

class TaxonomiaContenidoApp extends Eloquent {

    public $timestamps = false;
    protected $table = 'taxonomiasContenidosApp';
    protected $fillable = array('id_usuario', 'id_aplicacion', 'nombre', 'descripcion');

    /** Indica si una taxonomia existe para el usuario, dado el nombre del a taxonomia
     * 
     * @param String $nombreTax Nombre de la taxonomia
     * @return boolean
     */
    static function existe($nombreTax) {
        $taxs = TaxonomiaContenidoApp::where("id_usuario", "=", Auth::user()->id)->where("nombre", "=", $nombreTax)->get();
        if (count($taxs) > 0) {
            foreach ($taxs as $tax)
                break;
            return true;
        } else {
            return false;
        }
    }

    /** Obtiene un modelo de Taxonomia del usuario dado su nombre
     * 
     * @param type $nombreTax Nombre de la taxonomia
     * @return TaxonomiaContenidoApp Retorna el objeto en caso de exito de lo contrario Null
     */
    static function obtener($nombreTax) {
        $taxs = TaxonomiaContenidoApp::where("id_usuario", "=", Auth::user()->id)->where("nombre", "=", $nombreTax)->get();
        if (count($taxs) > 0) {
            foreach ($taxs as $tax)
                return $tax;
        } else {
            return null;
        }
    }

    //***********************************************
    //RELACION CON OTROS MODELOS*********************
    //***********************************************
    public function terminocontenidoapps() {
        return $this->hasMany('TerminoContenidoApp', 'id_taxonomia');
    }

}
