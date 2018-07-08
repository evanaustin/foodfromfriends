<?php
 
class Meetup extends Base {
    
    public
        $id,
        $grower_operation_id,
        $title,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $zipcode,
        $latitude,
        $longitude;

    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'meetups';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
    
}

?>