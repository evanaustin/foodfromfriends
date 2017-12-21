<?php

abstract class Base {

    function __construct($parameters) {
        foreach ($this->class_dependencies as $class) {
            $this->{$class} = null;
            if (isset($parameters[$class])) $this->{$class} = $parameters[$class];
        }
    }

    protected function configure_object($id) {
        $results = $this->DB->run("
            SELECT * FROM {$this->table} WHERE id=:id LIMIT 1
        ", [
            'id' => $id
        ]);
        
        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
    }

    public function exists($field, $data, $table = null) {
        if (!isset($table)) {
            $table = $this->table;
        }
        
        $results = $this->DB->run("
            SELECT * FROM {$table} WHERE {$field}=:data LIMIT 1
        ", [
            'data' => $data
        ]);
        
        return (isset($results[0])) ? $results[0] : false;
    }

    public function retrieve($field = null, $data = null, $table = null, $recent = false) {
        if (!isset($table)) {
            $table = $this->table;
        }

        if (!isset($field) && !isset($data)) {
            $results = $this->DB->run("
                SELECT * FROM {$table}  
            ");
        
            return (isset($results)) ? $results : false;
        } else if (isset($field) && isset($data)) {
            $bind = [
                'data' => $data
            ];
            
            if (!$recent) {
                $results = $this->DB->run("
                    SELECT * FROM {$table} WHERE {$field}=:data ORDER BY id asc
                ", $bind);
            } else {
                $results = $this->DB->run("
                    SELECT * FROM {$table} WHERE {$field}=:data ORDER BY id desc
                ", $bind);
            }
            
            return (isset($results)) ? $results : false;
        } else {
            return false;
        }
    }

    public function add($fields, $table = null) {
        if (!isset($table)) {
            $table = $this->table;
        }
        
        $results = $this->DB->insert($table, $fields);
        
        return (isset($results)) ? $results : false;
    }

    public function delete($field = null, $data = null, $table = null) {
        if (!isset($field)) {
            $field = 'id';
        }

        if (!isset($data)) {
            $data = $this->id;
        }

        if (!isset($table)) {
            $table = $this->table;
        }

        $success = $this->DB->delete($table, "{$field}=:data", [
            'data' => $data
        ]);

        return ($success) ? true : false;
    }

    public function update($info, $field = null, $data = null, $table = null) {
        if (!isset($field)) {
            $field = 'id';
        }

        if (!isset($data)) {
            $data = $this->id;
        }
       
        if (!isset($table)) {
            $table = $this->table;
        }

        $results = $this->DB->update($table, $info, "{$field}=:data", [
            'data' => $data
        ]);

        return (isset($results)) ? $results : false;
    }

    public function add_image($filepath, $file) {
        $img_saved = $this->S3->save_object($filepath, $file);

        return (isset($img_saved)) ? $results : false;
    }
    
    public function remove_image($filepath, $file) {
        $img_saved = $this->S3->delete_objects($filepath, $file);

        return (isset($img_saved)) ? $results : false;
    }
} 

?>