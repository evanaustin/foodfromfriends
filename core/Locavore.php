<?php
 
class Locavore extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'locavores';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    }
}

?>