<?php

    class Template
    {

        public function __construct($templateDir='tpl')
        {
            $this->templateDir = rtrim($templateDir,'/');
        }

        public function render($file,$vars = [])
        {
            $path = "{$this->templateDir}/{$file}.tpl";

            if (!file_exists($path))
                throw new Exception("La plantilla {$file} no existe en {$this->templateDir}");

            $contenido = file_get_contents($path);
            


            foreach($vars as $key => $value)
            {
                $contenido = preg_replace('/{{\s*'. preg_quote($key,'/') .'\s*}}/', htmlspecialchars($value),$contenido );
            }

            return $contenido;
        }

        
        

        static function header($titulo,$descripcion='',$author='1DAW')
        {
            $template = new Template();

            return $template->render('header',[
                'titulo'      => $titulo
               ,'description' => $descripcion
               ,'author'      => $author
            ]);

        }


        static function nav()
        {

            $template = new Template();

            return $template->render('navegacion',[
                'portfolio' => Idioma::lit('portfolio')
               ,'acercade'  => Idioma::lit('acercade')
               ,'contacto'  => Idioma::lit('contacto')
               ,'usuarios'  => Idioma::lit('usuarios')
               ,'calendario'  => Idioma::lit('calendario')
               ,'tutores'  => Idioma::lit('tutores')
            ]);

        }


        static function footer(){
            
            $template = new Template();

            return $template->render('footer');

        }


        static function seccion($seccion)
        {

            switch($seccion)
            {
                case 'usuarios':
                    $contenido = UsuarioController::pintar();
                break;

                case 'calendario':
                    $contenido = CalendarioController::pintar();
                break;

                case 'tutores':
                            $contenido = TutoresController::pintar();
                        break;

                case 'horario': // <-- ¡NUEVA RUTA AJAX AÑADIDA!
                    // 1. Obtener el ID del profesor desde la petición
                    $id_profesor = Campo::val('id_profesor') ?? 0;

                    // 2. Consulta SQL para el horario (UNE horarios, módulos y aulas)
                    $sql = "SELECT H.dia, H.hora_inicio, M.siglas, A.nombre AS aula
                            FROM horarios H
                            JOIN modulos M ON H.id_modulo = M.id
                            JOIN aulas A ON H.id_aula = A.id
                            WHERE H.id_profesor = {$id_profesor}
                            ORDER BY FIELD(H.dia, 'L', 'M', 'X', 'J', 'V'), H.hora_inicio";
                    
                    // Asume que Query.php está disponible
                    $horario_data = (new Query($sql))->recuperarTodo();
                    
                    // 3. Generar el HTML de la tabla con la función del controlador y devolverlo
                    $contenido = TutoresController::generarTablaHorario($horario_data);
                break;

                case 'tutores':
                    $contenido = TutoresController::pintar();
                break;

                default:
                    $contenido = PortadaController::pintar();
                break;
            }

            return $contenido;


        }

        static function navegacion($total_registros, $pagina)
        {
            $pagina_siguiente = ($total_registros == LISTADO_TOTAL_POR_PAGINA)?  "<li class=\"page-item\"><a class=\"page-link\" href=\"/usuarios/{$pagina}\">Siguiente</a></li>" : '';
            $pagina_anterior  = ($pagina != 1)? "<li class=\"page-item\"><a class=\"page-link\" href=\"/usuarios/". ($pagina-2) ."\">Anterior</a></li>" : '';

            return "
                <nav>
                    <ul class=\"pagination\">
                        {$pagina_anterior}
                        {$pagina_siguiente}
                    </ul>
                </nav>
            ";



        }

    }