<?php

// Asume que estas clases están disponibles en tu includes/general.php
// require_once 'modelos/Profesor.php'; // Clase para las operaciones CRUD
// require_once 'modelos/Curso.php';    // Clase para obtener la lista de cursos

class TutoresController {


    // PROPIEDADES AÑADIDAS PARA LA GESTIÓN DE TUTORÍA
    static $es_tutor, $curso;

    // Propiedades de la persona base
    static $paso, $oper, $id, $nombre, $apellidos, $email;


    static function pintar()
    {
        $contenido = '';

        self::inicializacion_campos();

        // Si la operación implica un envío de formulario, se podría gestionar aquí
        // if (Campo::val('paso') == 2) { ... } 

        switch(Campo::val('oper'))
        {
            case 'cons':
                $contenido = self::cons();
            break;
            case 'modi':
                $contenido = self::modi();
            break;
            case 'baja':
                $contenido = self::baja();
            break;
            case 'alta':
                $contenido = self::alta();
            break;
            default:
                $contenido = self::listado();
            break;
        }

        // Si no es petición AJAX, se añaden las cabeceras HTML del listado
        if (Campo::val('modo') != 'ajax')
        {
            $h1cabecera = "<h1>". Idioma::lit('titulo'.Campo::val('oper'))." ". Idioma::lit(Campo::val('seccion')) ."</h1>";
        }

      
        return "
        <div class=\"container contenido\">
        <section class=\"page-section tutores\" id=\"tutores\">
            {$h1cabecera}
            {$contenido}
            <div class='modal fade' id='modalHorario' tabindex='-1'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title'>Horario del Profesor</h5>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                        </div>
                        <div class='modal-body' id='div_horario_ajax_content'>
                            </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
        ";
    }

    static function inicializacion_campos()
    {
        // Campos de control
        self::$paso      = new Hidden(['nombre' => 'paso']);
        self::$oper      = new Hidden(['nombre' => 'oper']);
        self::$id        = new Hidden(['nombre' => 'id']);
        
        // Campos del profesor (persona)
        self::$nombre    = new Text(['nombre' => 'nombre']);
        self::$apellidos = new Text(['nombre' => 'apellidos']);
        self::$email     = new IEmail(['nombre' => 'email']);
        
        // CAMPOS DE TUTORÍA (NUEVOS EN EL EXAMEN)
        // Checkbox para indicar si es tutor
        self::$es_tutor  = new Checkbox(['nombre' => 'es_tutor', 'value' => 1, 'etiqueta' => 'Es Tutor de Grupo']); 
        
        // Select para elegir el curso de tutoría
        // Asume que Curso::getLista() devuelve un array [id => nombre_curso]
        $cursos_lista = Curso::getLista();
        self::$curso     = new Select(['nombre' => 'id_curso_tutoria', 'datos' => $cursos_lista, 'etiqueta' => 'Grupo a Tutorizar']);


        // Carga de elementos en el Formulario
        Formulario::cargar_elemento(self::$paso);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$nombre);
        Formulario::cargar_elemento(self::$apellidos);
        Formulario::cargar_elemento(self::$email);
        Formulario::cargar_elemento(self::$es_tutor);
        Formulario::cargar_elemento(self::$curso); // Este campo se oculta/muestra con JS
    }


    // ==========================================================
    // MÉTODOS CRUD (Esqueletos)
    // ==========================================================

    static function listado()
    {
        // Asume: $profesores = Profesor::getAll(); // Obtiene todos los profesores
        $profesores = (new Query("SELECT id, nombre, apellidos FROM personas WHERE tipo = 'P'"))->recuperarTodo();

        $html = "<h2>Listado de Profesores</h2>";
        $html .= "<a href='/?seccion=tutores&oper=alta' class='btn btn-success'>Nuevo Profesor</a>";
        $html .= "<table class='table'><thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead><tbody>";
        
        foreach($profesores as $p) {
            $html .= "<tr>";
            $html .= "<td>{$p->id}</td>";
            $html .= "<td>{$p->nombre} {$p->apellidos}</td>";
            $html .= "<td>
                        <a href='/?seccion=tutores&oper=modi&id={$p->id}' class='btn btn-sm btn-primary'>Editar</a>
                        <a href='#' 
                           class='btn btn-sm btn-info btn-ver-horario' 
                           data-profesor-id='{$p->id}' 
                           data-toggle='modal' data-target='#modalHorario'>Ver Horario</a>
                        <a href='/?seccion=tutores&oper=baja&id={$p->id}' class='btn btn-sm btn-danger'>Baja</a>
                    </td>";
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";
        
        return $html;
    }

    static function cons()
    {
        // Aquí se carga el profesor y se pinta la vista de consulta
        return "<p>Lógica de Consulta (Cons)</p>";
    }
    static function modi()
    {
        // Aquí se gestiona el formulario de modificación y la llamada a Profesor::save()
        return "<p>Lógica de Modificación (Modi)</p>";
    }
    static function baja()
    {
        // Aquí se confirma la baja y se llama a Profesor::delete()
        return "<p>Lógica de Baja (Baja)</p>";
    }
    static function alta()
    {
        // Aquí se gestiona el formulario de alta y la llamada a Profesor::save()
        return "<p>Lógica de Alta (Alta)</p>";
    }
    
    // ==========================================================
    // FUNCIÓN DE AYUDA PARA AJAX (Genera la tabla)
    // ==========================================================
    
    /**
     * Función que genera el HTML de la tabla de horario a partir de los datos de la DB.
     * Se llama desde Template::seccion('horario')
     */
    public static function generarTablaHorario(array $horario_data)
    {
        if (empty($horario_data)) {
            return "<p class='alert alert-warning'>No se encontró horario para este profesor.</p>";
        }

        // Días definidos en la BD: L, M, X, J, V
        $dias = ['L' => 'Lunes', 'M' => 'Martes', 'X' => 'Miércoles', 'J' => 'Jueves', 'V' => 'Viernes'];
        $horario_agrupado = [];
        
        // Agrupar los resultados de la consulta por día
        foreach ($horario_data as $clase) {
            $dia_letra = $clase->dia;
            if (!isset($horario_agrupado[$dia_letra])) {
                $horario_agrupado[$dia_letra] = [];
            }
            $horario_agrupado[$dia_letra][] = $clase;
        }

        $html = "<table class='table table-bordered table-sm'>";
        
        foreach ($dias as $letra => $nombre_dia) {
            if (isset($horario_agrupado[$letra])) {
                $html .= "<thead><tr><th colspan='3' class='table-dark'>{$nombre_dia}</th></tr></thead>";
                $html .= "<tbody>";
                foreach ($horario_agrupado[$letra] as $clase) {
                    $html .= "<tr>
                                <td>".substr($clase->hora_inicio, 0, 5)."</td>
                                <td>**{$clase->siglas}**</td>
                                <td>Aula: {$clase->aula}</td>
                              </tr>";
                }
                $html .= "</tbody>";
            }
        }
        
        $html .= "</table>";
        return $html;
    }
}

?>