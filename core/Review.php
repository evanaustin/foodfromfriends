<?php
 
class Review extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'reviews';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);

        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

}

?>