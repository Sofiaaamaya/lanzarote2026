<?php


    class HorarioController
    {
    static function pintar()
    {
        // Enrutador básico para el controlador de Horario
        if (Campo::val('oper') == 'ver') {
            return self::ver();
        }
        return "<h1>Horario del profesor</h1>";
    }

    static function ver()
    {
        $id_profesor = Campo::val('id');
        
        // Mockup de horario
        $horas = ['08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00', '11:00 - 11:30 (R)', '11:30 - 12:30', '12:30 - 13:30', '13:30 - 14:30'];
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        
        $html = "<table class='table table-bordered table-striped'>";
        $html .= "<thead><tr><th>Hora</th>" . implode('', array_map(function($d){ return "<th>$d</th>";}, $dias)) . "</tr></thead>";
        $html .= "<tbody>";
        
        foreach($horas as $hora) {
            $html .= "<tr><td>$hora</td>";
            foreach($dias as $dia) {
                // Simulación aleatoria de clases
                $contenido_celda = (rand(0,2) == 0) ? "Clase ($id_profesor)" : ""; 
                if (strpos($hora, '(R)') !== false) $contenido_celda = "RECREO";
                $html .= "<td>$contenido_celda</td>";
            }
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";

        // Respuesta AJAX
        if (Campo::val('modo') == 'ajax') {
            header('Content-Type: application/json');
            echo json_encode([
                'titulo' => 'Horario del Profesor',
                'contenido' => $html
            ]);
            exit;
        }

        return $html;
    }

    }