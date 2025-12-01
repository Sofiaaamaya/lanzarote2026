<?php

class TablaHorario
{
    static $elementos = [];
    
    static function cargar_elemento($elemento)
    {
        self::$elementos[] = $elemento;
    }



    static function sincro_form_bbdd($registro)
    {
        foreach($registro as $clave => $valor)
        {
            Campo::val($clave ,$valor);
        }
    }



    static function pintar($action,$method='POST')
    {
        $contenido = '';
        foreach(self::$elementos as $elemento)
        {
            $contenido .= $elemento->pintar();
        }



        return "
            {$mensaje_exito}
            <form id=\"formGeneral\" action=\"{$action}\" method=\"$method\" >
            {$contenido}
            </form>
        ";


    }

}