<?php
// Fichero: includes/elementos/checkboxTutor.php
// AÑADIDO: Forzar la carga del padre Checkbox
require_once 'checkbox.php'; 

class CheckboxTutor extends Checkbox {
    
    function __construct($datos=[]){
        parent::__construct($datos);
    }
    
    function pintar(){
        // Simplemente llama al padre. El ID será id{nombre} -> ides_tutor.
        $checkbox_html = parent::pintar(); 
        return $checkbox_html;
    }
}

// IMPORTANTE: ELIMINA CUALQUIER CÓDIGO PHP QUE ESTÉ FUERA DE LA CLASE
// COMO ESTE, QUE PROVOCA ERRORES FATALES:
/*
$check = new CheckboxTutor(['nombre' => 'es_tutor', 'value' => 1]);
$select_cursos = new Select(['nombre' => 'id_curso_tutoria', 'datos' => Cursos::getTodos()]);

echo $check->pintar();
echo "<div id='contenedor_cursos' style='display: none;'>";
echo $select_cursos->pintar();
echo "</div>";
*/

// ESTA LÓGICA DEBE IR EN EL MÉTODO FORMULARIO() DEL CONTROLADOR.
?>