<?php
require_once 'base.php';
class Curso extends Base
{
    function __construct()
    {
        $this->tabla = 'cursos';
    }

// Fichero: includes/modelos/curso.php (Modificación para aislar get_rows)

    function cargar()
    {
        // Usamos Query.php directamente para saltarnos cualquier problema en get_rows() de Base
        $query = new Query("
            SELECT id, nombre_grado, curso_numero, letra 
            FROM cursos
        ");

        $cursos = [];
        
        // Usamos recuperar() para iterar sobre los resultados
        while ($registro = $query->recuperar()) 
        {
            // El formato de salida es '1 DAW A'
            $cursos[$registro['id']] = $registro['curso_numero'].' '.$registro['nombre_grado']. ' '. $registro['letra'];
        }

        return $cursos;
    }

}