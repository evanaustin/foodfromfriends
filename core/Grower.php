<?php
 
class Grower extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'growers';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    }
}

?>