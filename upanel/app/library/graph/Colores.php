<?php

class Colores {
    
    const IndianRed = "";
    const LightCoral = "";
    const Salmon = "";
    const DarkSalmon = "";
    const LightSalmon = "";
    const Crimson = "";
    const Red = "";
    const FireBrick = "";
    const DarkRed = "";
    const Pink = "";
    const LightPink = "";
    const HotPink = "";
    const DeepPink = "";
    const MediumVioletRed = "";
    const PaleVioletRed = "";
    const Coral = "";
    const Tomato = "";
    const OrangeRed = "";
    const DarkOrange = "";
    const Orange = "";
    const Gold = "";
    const Yellow = "";
    const PeachPuff = "";
    const PaleGoldenrod = "";
    const Khaki = "";
    const DarkKhaki = "";
    const Lavender = "";
    const Thistle = "";
    const Plum = "";
    const Violet = "";
    const Orchid = "";
    const Fuchsia = "";
    const Magenta = "";
    const MediumOrchid = "";
    const MediumPurple = "";
    const BlueViolet = "";
    const DarkViolet = "";
    const DarkOrchid = "";
    const DarkMagenta = "";
    const Purple = "";
    const Indigo = "";
    const SlateBlue = "";
    const DarkSlateBlue = "";
    const GreenYellow = "";
    const Chartreuse = "";
    const LawnGreen = "";
    const Lime = "";
    const LimeGreen = "";
    const PaleGreen = "";
    const LightGreen = "";
    const MediumSpringGreen = "";
    const SpringGreen = "";
    const MediumSeaGreen = "";
    const SeaGreen = "";
    const ForestGreen = "";
    const Green = "";
    const DarkGreen = "";
    const YellowGreen = "";
    const OliveDrab = "";
    const Olive = "";
    const DarkOliveGreen = "";
    const MediumAquamarine = "";
    const DarkSeaGreen = "";
    const LightSeaGreen = "";
    const DarkCyan = "";
    const Teal = "";
    const Aqua = "";
    const Cyan = "";
    const LightCyan = "";
    const PaleTurquoise = "";
    const Aquamarine = "";
    const Turquoise = "";
    const MediumTurquoise = "";
    const DarkTurquoise = "";
    const CadetBlue = "";
    const SteelBlue = "";
    const LightSteelBlue = "";
    const PowderBlue = "";
    const LightBlue = "";
    const SkyBlue = "";
    const LightSkyBlue = "";
    const DeepSkyBlue = "";
    const DodgerBlue = "";
    const CornflowerBlue = "";
    const MediumSlateBlue = "";
    const RoyalBlue = "";
    const Blue = "";
    const MediumBlue = "";
    const DarkBlue = "";
    const Navy = "";
    const MidnightBlue = "";
    const BlanchedAlmond = "";
    const Bisque = "";
    const NavajoWhite = "";
    const Wheat = "";
    const BurlyWood = "";
    const Tan = "";
    const RosyBrown = "";
    const SandyBrown = "";
    const Goldenrod = "";
    const DarkGoldenrod = "";
    const Peru = "";
    const Chocolate = "";
    const SaddleBrown = "";
    const Sienna = "";
    const Brown = "";
    const Maroon = "";
    const Silver = "";
    const DarkGray = "";
    const Gray = "";
    const DimGray = "";
    const LightSlateGray = "";
    const SlateGray = "";
    const DarkSlateGray = "";
    const Black = "";

    static function todos() {
        $class = new ReflectionClass(__CLASS__);
        $colores = array();
        foreach ($class->getConstants() as $index => $value) {
            $colores[] = $index;
        }
        return $colores;
    }

    /** Obtiene un color aleatorio
     * 
     */
    static function obtenerAleatorio() {
        $colores = Colores::todos();
        return $colores[rand(0, count($colores) - 1)];
    }

}
