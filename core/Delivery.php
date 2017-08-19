<?php
 
class Delivery extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $user_id,
        $is_offered,
        $distance,
        $delivery_type,
        $free_miles,
        $fee,
        $pricing_rate;
     
    function __construct($parameters) {
        $this->table = 'delivery_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    function get_details($user_id) {
        $results = $this->DB->run('
            SELECT * FROM delivery_settings WHERE user_id = :user_id LIMIT 1
        ', [
            'user_id' => $user_id,
        ]); 
        
        return (isset($results[0])) ? $results[0] : false;
    }
}

?>