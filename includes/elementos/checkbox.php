<?php

class Checkbox extends Elemento {

   
    function __consruct($datos=[]){
        
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

    

    $checkbox_html = "
        <div class=\"form-check\">
            <input>
                <class=\"form-check_input {$this->style}\"
                type=\"{$this->type}
                value=\"{$this->value}
                nombre=\"{$this->nombre}
                id=\"{$this->nombre}
                {$checked} {$this->disabled}
                <label class=\"form-check-label\" form=\"id{$this->nombre}\" 
                \" .Idioma::lit($this->nombre) . \"
                </label>
                {$this->literal_error}
            </input>
        </div>ac
        ";


        return $checkbox_html;
        }
    
        

    

 
}