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

    /**
     * $params = []
     *  'where' => ['field' => 'data']
     *  'group' => string
     *  'order' => string
     *  'table' => string
     *  'limit' => int
     */
    public function retrieve($params = null) {
        if (isset($params)) {
            foreach ($params as $k => $v) ${$k} = $v;
        }
        
        if (!isset($table)) {
            $table = $this->table;
        }
        
        $sql = "SELECT * FROM {$table}";

        if (!isset($where)) {
            if (isset($limit)) $sql .= " LIMIT {$limit}";

            $results = $this->DB->run($sql);
        
            return (isset($results)) ? $results : false;
        } else if (isset($where)) {
            $bind = [];
            $i = 0;
            
            foreach ($where as $field => $data) {
                $sql .= (($i == 0) ? " WHERE " : " AND ") . "{$field}" . ((isset($data)) ? "=:" . $field : " IS NULL");
                
                if (isset($data)) $bind[$field] = $data;

                $i++;
            }
            
            if (isset($group)) $sql .= " GROUP BY {$group}";

            if (isset($order)) $sql .= " ORDER BY {$order}";

            if (isset($limit)) $sql .= " LIMIT {$limit}";
            
            $results = $this->DB->run($sql, $bind);
            
            return (!empty($results)) ? (isset($limit) && $limit === 1 ? $results[0] : $results) : false;
        } else {
            $results = $this->DB->run($sql);
        
            return (isset($results[0])) ? $results[0] : false;
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

    public function gen_referral_key($len, $name = null) {
        $slug = strtoupper(preg_replace('/[\s\-\_]+/', '', $name));
        $code = substr(md5(microtime()), rand(0,26), $len);
        
        return (!empty($slug) ? $slug . '_' . $code : $code);
    }
} 

?>