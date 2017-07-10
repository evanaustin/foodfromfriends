<?php

abstract class Base {

    function __construct($parameters) {
        foreach ($this->class_dependencies as $class) {
            $this->{$class} = null;
            if (isset($parameters[$class])) $this->{$class} = $parameters[$class];
        }
    }
    
    public function exists($table, $field, $data) {
        $bind = [
            'data' => $data
        ];

        $results = $this->DB->run("
            SELECT * FROM {$table} WHERE {$field}=:data LIMIT 1
        ", $bind);
        
        return (isset($results[0])) ? true : false;
    }

    public function add($table, $fields) {
        $results = $this->DB->insert($table, $fields);
        
        return (isset($results)) ? $results : false;
    }
}     

?>