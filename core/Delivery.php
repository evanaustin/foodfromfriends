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
        $free_delivery,
        $free_miles,
        $pricing_rate,
        $fee;
     
    function __construct($parameters) {
        $this->table = 'delivery_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    function update($info, $field, $data) {
        $results = $this->DB->update($this->table, $info, "{$field}=:data", [
            'data' => $data
        ]);

        return (isset($results)) ? $results : false;
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