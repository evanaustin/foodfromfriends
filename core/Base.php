<?php

abstract class Base {

    function __construct($parameters) {
        foreach ($this->class_dependencies as $class) {
            $this->{$class} = null;
            if (isset($parameters[$class])) $this->{$class} = $parameters[$class];
        }
    }
    
    public function add($table, $fields) {
        $results = $this->DB->insert($table, $fields);
        
        return (isset($results)) ? $results : false;
    }
    
    public function exists($table,$field, $data) {
       
       
        $bind = [
            $field => $data
        ];
        
    }     


}

?>