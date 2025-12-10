<?php

    class Select extends Elemento
    {

        function __construct($datos=[])
        {
            parent::__construct($datos);

        }

        function validar()
        {
            if(empty(Campo::val($this->nombre)))
            {
                $this->error = True;
                Formulario::$numero_errores++;
            }
        }



    function pintar()
    {

        $this->previo_pintar();


        $options = empty($this->nombre)? '' : '<option>'. Idioma::lit('placeholder'.$this->nombre) .'</option>';
        if ($this->options && is_array($this->options)) {
            foreach($this->options as $value => $literal_value)
            {
            /*
            $selected = '';
            if ($value == Campo::val($this->nombre))
                $selected = 'selected';
            */

            $selected = $value == Campo::val($this->nombre)? 'selected' : '';

            $options .= "<option value=\"{$value}\" {$selected}>{$literal_value}</option>";
            }
        }

        


        $options = "<select class=\"{$this->style} form-select\" id=\"id{$this->nombre}\" {$this->disabled} name=\"{$this->nombre}\">".$options.'</select>';



        return "
            {$this->previo_envoltorio}
                {$this->literal_error}
                {$options}
            {$this->post_envoltorio}
        ";
    }

    }




    
/*

class cb extends Elemento{
    function __consruct($datos=[]){
        $datos=['esqueleto'] = False;
        $datos=['type'] = 'checkbox';
        $this->value = $datos['value'] ?? '1';
        parent::__construct($datos);

    }
    function validar(){
    $this->error = False;
    }

    function pintar(){
    $this->previo_pintar();
    $checked = (Campo::val($this->nombre) == $this->value) ? 'checked' : '';

    

    $cb_html = "
        <div class="form-check">
            <input>
                <class="form-check_input {$this->style}"
                type="{$this->type}
                value="{$this->value}
                nombre="{$this->nombre}
                id="{$this->nombre}
                {$checked} {$this->disabled}
                <label class="form-check-label" form="id{$this->nombre}" 
                ".Idioma::lit($this->nombre)
                </label>
                {$this->literal_error}
            </input>
        </div>
        ";
        }
    
        return $cb_html;


}

*/ 