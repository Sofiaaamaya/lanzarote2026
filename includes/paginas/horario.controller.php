<?php

    class HorarioController {

        static $modulo, $id = '';

        static function pintar(){

            $contenido = '
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Dropdown button
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </div>
            ';

        return "
        <div class=\"container contenido\">
        <section class=\"page-section horarios\" id=\"horarios\">
            {$contenido}
        </section>
        </div>
        
        ";
        }




    static function listado()
    {
        $horario = new Horario();

        $listado_horarios= '';
        $total_registros = 0;
        foreach($datos_consulta as $indice => $registro)
        {


            $listado_horarios .= "
                <tr>
                    <td>{$dia['dia']}</td>
                    <td>{$hora_nicio['hora_inicio']}</td>
                    <td>{$hora_fin['hora_fin']}</td>
                    <td>{$id_modulo['id_modulo']}</td>
                    <td>{$id_profesor['id_profesor']}</td>
                    <td>{$id_aula['id_aula']}</td>

                    <td>". fmto_fecha($registro['usuario_alta']) . "</td>
                    <td>". fmto_fecha($registro['usuario_baja']) . "</td>
                </tr>
            ";

            $total_registros++;
        }


        $barra_navegacion = Template::navegacion($total_registros,$pagina);


        return "
            <table class=\"table\">
            <thead>
                <tr>
                <th scope=\"col\">#</th>
                <th scope=\"col\">dia</th>
                <th scope=\"col\">Inicio</th>
                <th scope=\"col\">Fin</th>
                <th scope=\"col\">modulo</th>
                <th scope=\"col\">profesor</th>
                <th scope=\"col\">Aula</th>
                <th scope=\"col\">Fecha Alta</th>
                <th scope=\"col\">Fecha Baja</th>
                </tr>
            </thead>
            <tbody>
            {$listado_horarios}
            </tbody>
            </table>
            ";

    }

    static function horario(){
        return Horario::pintar('/horarios/');

    }


    static function inicializacion_campos()
    {
        self::$dia        = new Text(['nombre' => 'dia']);
        self::$hora_nicio      = new Text(['nombre' => 'hora_nicio']);
        self::$hora_fin    = new Text(['nombre' => 'hora_fin']);
        self::$id_modulo = new Text(['nombre' => 'id_modulo']);
        self::$id_profesor  = new Text(['nombre' => 'id_profesor']);
        self::$id_aula     = new Text(['nombre' => 'id_aula']);


        Horario::cargar_elemento(self::$dia);
        Horario::cargar_elemento(self::$hora_nicio);
        Horario::cargar_elemento(self::$hora_fin);
        Horario::cargar_elemento(self::$id_modulo);
        Horario::cargar_elemento(self::$id_profesor);
        Horario::cargar_elemento(self::$id_aula);

    }


    }

?>