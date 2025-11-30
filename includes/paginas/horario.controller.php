<?php
    if (Campo::val('modo') == 'ajax')
        define('BOTON_ENVIAR',"<button onclick=\"fetchJSON('/usuarios/".Campo::val('oper')."/". Campo::val('id') ."?modo=ajax','formulario');return false\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
    else
        define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");

    class HorarioController{

        static $nombre_modulo, $profesor, $siglas_modulo, $curso, $letra, $color ;


        static function pintar(){

            $contenido = "";

            self::inicializacion_campos();
        }





    }

?>