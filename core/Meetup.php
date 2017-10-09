<?php
 
class Meetup extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'meetup_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    public function get_details($grower_operation_id) {
        $results = $this->DB->run('
            SELECT * FROM meetup_settings WHERE grower_operation_id = :grower_operation_id LIMIT 1
        ', [
            'grower_operation_id' => $grower_operation_id,
        ]); 
        
        return (isset($results[0])) ? $results[0] : false;
    }
}

?>