<?php
 
class Grower extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $table,
        $id,
        $email,
        $password,
        $first_name,
        $last_name,
        $validation,
        $registered_on;
        
    
    function __construct($parameters) {
        $this->table = 'growers';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
}

?>