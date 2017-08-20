<?php
 
class Pickup extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $user_id,
        $instructions,
        $availability;
     
    function __construct($parameters) {
        $this->table = 'pickup_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
    
    function get_details($user_id) {
        $results = $this->DB->run('
            SELECT * FROM pickup_settings WHERE user_id = :user_id LIMIT 1
        ', [
            'user_id' => $user_id,
        ]); 
        
        return (isset($results[0])) ? $results[0] : false;
        }
}

?>