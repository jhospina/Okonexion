<?php

/**
 * Nombre de la clase: Imagen
 * Descripcion: Esta clase controla y provee funcionalidad de una imagen
 *  Desarrollador por: John H. Ospina
 * Correo: jhonospina150@gmail.com
 */
class Imagen {

    public $nombre;
    public $extension;
    public $ancho;
    public $altura;
    public $mime_type;
    public $ruta;
    public $path;
    public $objectImage;
    public $ruta_url;

    function __construct($url) {

        $this->nombre = Util::extraerNombreArchivo($url);
        $this->extension = Util::extraerExtensionArchivo($url);
        $this->path = Util::convertirUrlPath($url);
        $this->ruta = str_replace($this->nombre . "." . $this->extension, "", $this->path);
        $this->ruta_url = str_replace($this->nombre . "." . $this->extension, "", $url);
        $this->mime_type = mime_content_type($this->path);
        $dimensiones = getimagesize($this->path);
        $this->ancho = $dimensiones[0];
        $this->altura = $dimensiones[1];
    }

    /** Crea una copia redimensionada y recortada adecuadamente sin defases de la imagen original y la almacena en el servidor con nuevo nombre
     * 
     * @param type $ancho Ancho de la copia
     * @param type $altura Altura de la copia
     * @param type $nombre Nombre de la copia
     * @param type $ruta La ruta donde se almacenara la copia
     */
    public function crearCopia($ancho, $altura, $nombre, $ruta) {

        $this->objectImage = $this->crearImagenDesdeOriginal();

        //Calcula los valores optimos a redimensioar sin desfasarse en los tamaños requeridos
        list($ancho_redim, $altura_redim) = $this->calcularRedimensionMinimaProporcional($ancho, $altura);

        $copia_redim = imagecreatetruecolor($ancho_redim, $altura_redim);

        $fondo = imagecolorallocate($copia_redim, 50, 255, 0);

        imagefilledrectangle($copia_redim, 0, 0, $ancho_redim, $altura_redim, $fondo);
        imagecolortransparent($copia_redim, $fondo);
        
        //Redimensiona la imagen al proporcion adecuada
        imagecopyresized($copia_redim, $this->objectImage, 0, 0, 0, 0, $ancho_redim, $altura_redim, $this->ancho, $this->altura);

        //Almacenara la copia de la imagen redimensiona y recortada adecuadamente
        $copia_rec = imagecreatetruecolor($ancho, $altura);

        $fondo = imagecolorallocate($copia_rec, 50, 255, 0);

        imagefilledrectangle($copia_rec, 0, 0, $ancho, $altura, $fondo);
        imagecolortransparent($copia_rec, $fondo);

        list($x_recorte, $y_recorte) = $this->calcularPosicionRecorte($ancho_redim, $altura_redim, $ancho, $altura);

        //Genera el recorte adecuado de la imagen
        imagecopy($copia_rec, $copia_redim, 0, 0, $x_recorte, $y_recorte, $ancho_redim, $altura_redim);

        $destino = $ruta . $nombre . "." . $this->extension;

        $this->almacenarImagen($copia_rec, $destino);
    }

    /** Calcula las dimensiones (ancho,altura) minimas de redimension proporcional de la imagen original sin desfasarse para las dimensiones requeridas
     * 
     * @param Int $ancho El ancho requirido a redimensionar
     * @param Int $altura La altura requerida a redimensionar
     * @return array AnchoxAltura
     */
    private function calcularRedimensionMinimaProporcional($ancho, $altura) {
        $altura_min = ($this->altura * $ancho) / $this->ancho;
        $ancho_min = ($this->ancho * $altura) / $this->altura;

        if ($altura_min < $altura)
            return array($ancho_min, $altura);
        elseif ($altura_min > $altura)
            return array($ancho, $altura_min);

        return array($ancho_min, $altura_min);
    }

    /** Calcula la posicion de recorte adecuada para una imagen. Esta posición siempre tendera a ser centrada. 
     * 
     * @param Int $ancho Ancho de la imagen origen
     * @param Int $altura Altura de la imagen origen
     * @param Int $ancho_recorte Ancho del recorte
     * @param Int $altura_recorte Altura del recorte
     * @return Int Retorna un array con la posicions X,Y calculadas para el recorte adecuado.
     */
    private function calcularPosicionRecorte($ancho, $altura, $ancho_recorte, $altura_recorte) {
        $x = 0;
        $y = 0;

        if ($ancho > $ancho_recorte && $altura > $altura_recorte) {
            $x = ($ancho - $ancho_recorte) / 2;
            $y = ($altura - $altura_recorte) / 2;
            return array($x, $y);
        }

        if ($ancho > $ancho_recorte) {
            $x = ($ancho - $ancho_recorte) / 2;
            return array($x, $y);
        }

        if ($altura > $altura_recorte) {
            $y = ($altura - $altura_recorte) / 2;
            return array($x, $y);
        }

        return array($x, $y);
    }

    /** Crea un objeto Image de la imagen original
     * 
     * @return Image
     */
    private function crearImagenDesdeOriginal() {
        switch ($this->extension) {
            case "jpg":
                $imagen = imagecreatefromjpeg($this->path);
                break;
            case "jpeg":
                $this->extension = "jpg";
                $imagen = imagecreatefromjpeg($this->path);
                break;
            case "png":
                $imagen = imagecreatefrompng($this->path);
                break;
            case "gif":
                $imagen = imagecreatefromgif($this->path);
                break;
        }
        return $imagen;
    }

    /** Crea una imagen en un archivo y lo almacena en el servidor
     * 
     * @param Image $imagen La imagen a guardar
     * @param type $destino El destino en donde se guardara la imagen
     * @param type $calidad [90] La calidad de la imagen con la que se guardara
     * @return type
     */
    private function almacenarImagen($imagen, $destino, $calidad = 9) {
        switch ($this->extension) {
            case "jpg":
                return imagejpeg($imagen, $destino, $calidad);
                break;
            case "jpeg":
                return imagejpeg($imagen, $destino, $calidad);
                break;
            case "png":
                return imagepng($imagen, $destino, $calidad);
                break;
            case "gif":
                return imagegif($imagen, $destino, $calidad);
                break;
        }
    }

    public function __toString() {
        return "Nombre: " . $this->nombre . "<br/>" .
                "Extension: " . $this->extension . "<br/>" .
                "Mime Type: " . $this->mime_type . "<br/>" .
                "Ruta: " . $this->ruta . "<br/>" .
                "Rura_URL" . $this->ruta_url . "<br/>";
        "Path: " . $this->path . "<br/>" .
                "Ancho: " . $this->ancho . " px<br/>" .
                "Altura: " . $this->altura . " px";
    }

    //***************************************************
    //GETS Y SETS DE PROPIEDADES*************************
    //***************************************************
    function getNombre() {
        return $this->nombre;
    }

    function getExtension() {
        return $this->extension;
    }

    function getAncho() {
        return $this->ancho;
    }

    function getAltura() {
        return $this->altura;
    }

    function getMime_type() {
        return $this->mime_type;
    }

    function getRuta() {
        return $this->ruta;
    }

    function getPath() {
        return $this->path;
    }

    function getRuta_url() {
        return $this->ruta_url;
    }

    function getObjectImage() {
        return $this->objectImage;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setExtension($extension) {
        $this->extension = $extension;
    }

    function setAncho($ancho) {
        $this->ancho = $ancho;
    }

    function setAltura($altura) {
        $this->altura = $altura;
    }

    function setMime_type($mime_type) {
        $this->mime_type = $mime_type;
    }

    function setRuta($ruta) {
        $this->ruta = $ruta;
    }

    function setPath($path) {
        $this->path = $path;
    }

    function setObjectImage($objectImage) {
        $this->objectImage = $objectImage;
    }

}
