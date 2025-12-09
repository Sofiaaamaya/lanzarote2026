<?php

class Profesor extends Base {

    public $id, $nombre, $email, $es_tutor, $id_curso_tutoria;

    public function guardar(){

        if ($this->id){
            // UPDATE
            $sql = "UPDATE personas SET nombre=\"{$this->nombre}\", email=\"{$this->email}\", 
                    es_tutor={$this->es_tutor}, id_curso_tutoria={$this->id_curso_tutoria} 
                    WHERE id = {$this->id}";
        } else {
            // CREATE
            $sql = "INSERT INTO personas (nombre, email, tipo, es_tutor, id_curso_tutoria)
                    VALUES (\"{$this->nombre}\", \"{$this->email}\", \"P\", {$this->es_tutor}, {$this->id_curso_tutoria})";
        }
        
        new Query($sql); // Usa la clase Query de tu profesor
    }

// MÉTODO DELETE
    public static function delete($id){
        new Query("DELETE FROM personas WHERE id = {$id} AND tipo = 'P'");
    }

    // MÉTODO GET ALL (READ - Listado)
    public static function getAll(){
        // Deberías devolver un array de objetos Profesor
        return (new Query("SELECT * FROM personas WHERE tipo = 'P'"))->recuperarTodo(); 
    }
}