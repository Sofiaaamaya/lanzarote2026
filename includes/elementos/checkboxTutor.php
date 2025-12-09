// Fichero: CheckboxTutor.php
class CheckboxTutor extends Checkbox {
    function pintar(){
        $checkbox_html = parent::pintar(); // Genera el HTML del checkbox

        // Añade el ID especial al input del checkbox para que JS lo detecte.
        // Asumiendo que el input tiene el id = $this->nombre, es decir 'es_tutor'
        $checkbox_html = str_replace('<input', '<input id="es_tutor_check"', $checkbox_html);

        return $checkbox_html;
    }
}

// En el formulario (al cargar)
$check = new CheckboxTutor(['nombre' => 'es_tutor', 'value' => 1]);
$select_cursos = new Select(['nombre' => 'id_curso_tutoria', 'datos' => Cursos::getTodos()]);

echo $check->pintar();
echo "<div id='contenedor_cursos' style='display: none;'>";
echo $select_cursos->pintar();
echo "</div>";