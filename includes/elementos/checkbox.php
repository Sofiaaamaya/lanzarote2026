// Fichero: includes/elementos/checkbox.php

<?php
// AÑADIDO: Forzar la carga del padre Elemento
require_once 'elemento.php'; 

class Checkbox extends Elemento {

   
    function __construct($datos=[]){
        
        $datos['esqueleto'] = False;

        $datos['type'] = 'checkbox';

        $this->value = $datos['value'] ?? '1';
        parent::__construct($datos);
    }
    
    function validar(){
        $this->error = False;
    }

    function pintar(){
        $this->previo_pintar();
        $checked = (Campo::val($this->nombre) == $this->value) ? 'checked' : '';
        
        // CORRECCIÓN CLAVE: El INPUT debe ser una etiqueta de cierre automático.
        $checkbox_html = "
            <div class=\"form-check\">
                <input class=\"form-check-input {$this->style}\"
                    type=\"{$this->type}\"
                    value=\"{$this->value}\"
                    name=\"{$this->nombre}\"
                    id=\"id{$this->nombre}\" 
                    {$checked} {$this->disabled}
                >
                <label class=\"form-check-label\" for=\"id{$this->nombre}\">
                    " . Idioma::lit($this->nombre) . "
                </label>
                {$this->literal_error}
            </div>
        ";
        
        return $checkbox_html;
    }
}
?>