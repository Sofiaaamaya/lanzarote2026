<?php
require_once 'base.php'; 

class Profesor extends Base
{
    public $es_tutor; 
    public $id_curso_tutoria; 
    public $id, $nombre, $apellidos, $email;
    public $tipo = 'P';
    public $fecha_baja;

    function __construct()
    {
        $this->tabla = 'personas';
    }

    // Código simplificado
    public static function getAll()
    {
        // Usamos SELECT p.* y el JOIN para obtener el nombre del curso para la columna del listado.
        $sql = "SELECT p.*, CONCAT(c.nombre_grado, ' ', c.curso_numero, c.letra) as nombre_curso_tutoria
                FROM personas p
                LEFT JOIN cursos c ON p.id_curso_tutoria = c.id
                WHERE p.tipo = 'P'"; 
                
        // Llama directamente a Query para ejecutar y devolver los datos.
        return (new Query($sql))->recuperarTodo(); 
    }

   
    public function guardar(){
        
        $es_tutor_db = $this->es_tutor ? 1 : 0; 
        $id_curso_tutoria_db = (int)$this->id_curso_tutoria > 0 ? $this->id_curso_tutoria : 'NULL';
        
        // El ID debe estar en el objeto si viene de modi
        if ($this->id){
            $datos_a_actualizar = [
                'nombre'           => $this->nombre,
                'apellidos'        => $this->apellidos,
                'email'            => $this->email,
                'es_tutor'         => $es_tutor_db,
                'id_curso_tutoria' => $id_curso_tutoria_db 
            ];
            return $this->actualizar($datos_a_actualizar, $this->id); 
            
        } else {
            // INSERT
            $datos_a_insertar = [
                'nombre'           => $this->nombre,
                'apellidos'        => $this->apellidos,
                'email'            => $this->email,
                'tipo'             => 'P', 
                'es_tutor'         => $es_tutor_db,
                'id_curso_tutoria' => $id_curso_tutoria_db
            ];
            return $this->insertar($datos_a_insertar); 
        }
    }
    

    public function darBaja()
    {
        return $this->eliminar($this->id);
    }
}
?>