<?php
 
class Meetup extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $user_id,
        $is_offered,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $zip,
        $time;
        
     
    function __construct($parameters) {
        $this->table = 'meetup_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    public function get_details($user_id) {
        $results = $this->DB->run('
            SELECT * FROM meetup_settings WHERE user_id = :user_id LIMIT 1
        ', [
            'user_id' => $user_id,
        ]); 
        
        return (isset($results[0])) ? $results[0] : false;
    }
}

?>