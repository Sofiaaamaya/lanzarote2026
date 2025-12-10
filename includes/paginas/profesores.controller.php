<?php

// Fichero: includes/paginas/profesores.controller.php

if (Campo::val('modo') == 'ajax')
    define('BOTON_ENVIAR',"<button onclick=\"fetchJSON('/profesores/".Campo::val('oper')."/". Campo::val('id') ."?modo=ajax','formulario');return false\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");
else
    define('BOTON_ENVIAR',"<button type=\"submit\" class=\"btn btn-primary\">". Idioma::lit('enviar'.Campo::val('oper'))."</button>");

class ProfesoresController
{
    // PROPIEDADES ESTÁTICAS CORREGIDAS Y COMPLETADAS
    static $paso, $oper, $id, $nombre, $apellidos, $email;
    static $es_tutor, $curso;

    static function pintar()
    {
        $contenido = '';

        self::inicializacion_campos();

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


        
        // El código del return original, que se ejecuta si quitas el die()
        if (Campo::val('modo') != 'ajax')
        {
            // Usamos 'profesores' para la sección
            $h1cabecera = "<h1>". Idioma::lit('titulo'.Campo::val('oper'))." ". Idioma::lit('profesores') ."</h1>"; 
        } else {
             $h1cabecera = '';
        }

      
        return "
        <div class=\"container contenido\">
        <section class=\"page-section profesores\" id=\"profesores\">
            {$h1cabecera}
            {$contenido}
            </section>
        </div>
        
        ";

    }


    static function inicializacion_campos()
    {
        // 1. Campos de control
        self::$paso      = new Hidden(['nombre' => 'paso']);
        self::$oper      = new Hidden(['nombre' => 'oper']);
        self::$id        = new Hidden(['nombre' => 'id']);
        
        // 2. Campos comunes de personas (CRUD)
        self::$nombre    = new Text(['nombre'   => 'nombre']);
        self::$apellidos = new Text(['nombre'   => 'apellidos']);
        self::$email     = new IEmail(['nombre' => 'email']); 

        // 3. CAMPOS DE TUTORÍA (EXAMEN)
        self::$es_tutor  = new Checkbox(['nombre' => 'es_tutor', 'value' => 1, 'etiqueta' => 'Es Tutor']); 

        $cursos_lista = Curso::cargar(); 

        self::$curso     = new Select(['nombre' => 'id_curso_tutoria', 'options' => $cursos_lista, 'etiqueta' => 'Curso de Tutoría']);
        
        // Carga de elementos en el Formulario
        Formulario::cargar_elemento(self::$paso);
        Formulario::cargar_elemento(self::$oper);
        Formulario::cargar_elemento(self::$id);
        Formulario::cargar_elemento(self::$nombre);
        Formulario::cargar_elemento(self::$apellidos);
        Formulario::cargar_elemento(self::$email);
        Formulario::cargar_elemento(self::$es_tutor); 
        Formulario::cargar_elemento(self::$curso); 
    }

    static function formulario($boton_enviar='',$errores=[],$mensaje_exito='',$disabled='')
    {
        Formulario::disabled($disabled);

        Campo::val('paso','1');

        // Contenedor del Select para la lógica JavaScript
        $select_curso_html = self::$curso->pintar();
        $select_curso_html_dinamico = "
            <div id='contenedor_curso_select' style='display: none;'>
                {$select_curso_html}
            </div>
        ";
        
        // Pintar todos los elementos y el contenedor dinámico
        $contenido_formulario = self::$nombre->pintar() . 
                                self::$apellidos->pintar() .
                                self::$email->pintar() . 
                                self::$es_tutor->pintar() . 
                                $select_curso_html_dinamico;


        return Formulario::pintar('/profesores/',$boton_enviar,$mensaje_exito);
    }

    static function sincro_form_bbdd($registro)
    {
        Formulario::sincro_form_bbdd($registro);
    }


    static function cons()
    {
        $profesor = new Profesor();

        $registro = $profesor->recuperar(Campo::val('id'));

        self::sincro_form_bbdd($registro);

        return self::formulario('',[],''," disabled=\"disabled\" ");
    }

    static function baja()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled =" disabled=\"disabled\" "; 

        if(!Campo::val('paso')) // PASO 1: Cargar datos para confirmación
        {
            $profesor = new Profesor();
            $registro = $profesor->recuperar(Campo::val('id')); // Recuperar por ID
            self::sincro_form_bbdd($registro);
        }
        else // PASO 2: Ejecución de la Baja Lógica
        {
            $profesor = new Profesor();
            $profesor->id = Campo::val('id'); 
            $profesor->darBaja(); // Llama al método de baja lógica del modelo
            
            $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';
            $boton_enviar = '';
        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);
    }


    static function modi()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled='';

        if(!Campo::val('paso'))
        {
            $profesor = new Profesor();
            $registro = $profesor->recuperar(Campo::val('id'));
            self::sincro_form_bbdd($registro);
        }
        else
        {
            $numero_errores = Formulario::validacion();

            if(!$numero_errores)
            {
                $profesor = new Profesor();
                $profesor->id = Campo::val('id'); // Necesario para el UPDATE
                $profesor->nombre = Campo::val('nombre');
                $profesor->apellidos = Campo::val('apellidos');
                $profesor->email = Campo::val('email');
                $profesor->es_tutor = Campo::val('es_tutor') ? 1 : 0;
                $profesor->id_curso_tutoria = Campo::val('id_curso_tutoria');

                $profesor->guardar(); // UPDATE

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';
                $disabled =" disabled=\"disabled\" ";
                $boton_enviar = '';
            }
        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);
    }

    static function alta()
    {
        $boton_enviar = BOTON_ENVIAR;
        $errores = [];
        $mensaje_exito='';
        $disabled='';

        if(Campo::val('paso'))
        {
            $numero_errores = Formulario::validacion();

            if(!$numero_errores)
            {
                $profesor = new Profesor();
                $profesor->nombre = Campo::val('nombre');
                $profesor->apellidos = Campo::val('apellidos');
                $profesor->email = Campo::val('email');
                $profesor->es_tutor = Campo::val('es_tutor') ? 1 : 0;
                $profesor->id_curso_tutoria = Campo::val('id_curso_tutoria');

                $profesor->guardar(); // INSERT

                $mensaje_exito = '<p class="centrado alert alert-success" >' . Idioma::lit('operacion_exito') .  '</p>';
                $disabled =" disabled=\"disabled\" ";
                $boton_enviar = '';
            }
        }

        return self::formulario($boton_enviar,$errores,$mensaje_exito,$disabled);
    }


    static function listado()
    {
        // Lógica de paginación
        $pagina = is_numeric(Campo::val('pagina')) ? Campo::val('pagina') : 0;
        $offset = LISTADO_TOTAL_POR_PAGINA * $pagina;
        $pagina++;
        
        $datos_consulta = Profesor::getAll(); // Asume que getAll() retorna un array de ARRAYS ASOCIATIVOS
        


        $listado_profesores = '';
        $total_registros = 0;
        
        foreach($datos_consulta as $indice => $registro)
        {
            // Botones CRUD y el botón AJAX (Ver Horario)
            $boton_alumnos = '';
            if ($registro['es_tutor'] && $registro['id_curso_tutoria']) {
                // Enlace a alumnos (solo lectura/listado)
                $boton_alumnos = "<a href=\"/alumnos/listado/{$registro['id_curso_tutoria']}\" class=\"btn btn-warning\" title=\"Ver Alumnos\"><i class=\"bi bi-people\"></i></a>";
            }

            $botonera = "
                <a onclick=\"fetchJSON('/profesores/cons/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-secondary\"><i class=\"bi bi-search\"></i></a>
                
                <a onclick=\"fetchJSON('/profesores/modi/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-primary\"><i class=\"bi bi-pencil-square\"></i></a>

                <a onclick=\"fetchJSON('/horario/ver/{$registro['id']}?modo=ajax')\" data-bs-toggle=\"modal\" data-bs-target=\"#ventanaModal\" class=\"btn btn-info\"><i class=\"bi bi-clock\"></i></a>
                
                {$boton_alumnos}

                <a href=\"/profesores/baja/{$registro['id']}\" class=\"btn btn-danger\"><i class=\"bi bi-trash\"></i></a>
            ";

            $listado_profesores .= "
                <tr>
                    <th style=\"white-space:nowrap\" scope=\"row\">{$botonera}</th>
                    <td>{$registro['id']}</td>
                    <td>{$registro['nombre']}</td>
                    <td>{$registro['apellidos']}</td>
                    <td>{$registro['email']}</td>
                    <td>". ($registro['es_tutor'] ? "Sí" : "No") . "</td>
                    <td>". ($registro['nombre_curso_tutoria'] ?? "N/A") . "</td>
                </tr>
            ";
            $total_registros++; // Contador para la paginación
        }
                
        $barra_navegacion = Template::navegacion($total_registros, $pagina);


        return "
            <table class=\"table\">
            <thead>
                <tr>
                <th scope=\"col\">#</th>
                <th scope=\"col\">ID</th>
                <th scope=\"col\">Nombre</th>
                <th scope=\"col\">Apellidos</th>
                <th scope=\"col\">Email</th>
                <th scope=\"col\">Tutor</th>
                <th scope=\"col\">Curso</th>
                </tr>
            </thead>
            <tbody>
            {$listado_profesores}
            </tbody>
            </table>
            {$barra_navegacion}
            <a href=\"/profesores/alta\" class=\"btn btn-primary\"><i class=\"bi bi-file-earmark-plus\"></i> Alta profesor</a>
            ";
    }
    // El resto de funciones (generarTablaHorario) ya están definidas en el controlador.
}