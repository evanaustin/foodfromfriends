<?php
 
class Pickup extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'pickup_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
    
    /**
     * @todo rearchitect so delivery_id is stored in GrowerOperation & rm grower_operation_id from Delivery
     */
    public function get_details($grower_operation_id) {
        $results = $this->DB->run('
            SELECT * FROM pickup_settings WHERE grower_operation_id = :grower_operation_id LIMIT 1
        ', [
            'grower_operation_id' => $grower_operation_id,
        ]); 
        
        return (isset($results[0])) ? $results[0] : false;
    }
}

?>