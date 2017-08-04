<?php
 
class Meetup extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $user_id,
        $address_line_1,
        $address_line_2,
        $when_details;
        
     
    function __construct($parameters) {
        $this->table = 'meetup_settings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    function add_details($fields){

        $results = $this->DB->insert('meetup_details', $fields);
        
        return (isset($results)) ? $results : false;
    }

    function update_details($info, $conditions) {
        $sql_conditions = '';
        $i = 0;

        foreach($conditions as $k => $v) {
            if ($i > 0) {
                $sql_conditions .= ' AND ';
            }
            
            $sql_conditions .= $k . '=:' . $k;

            $i++;
        }

        $results = $this->DB->update('meetup_details', $info, $sql_conditions, $conditions);

        return $results;
    }

    function exists_details($user_id, $details_id) {
     
        $results = $this->DB->run("
            SELECT * FROM meetup_details WHERE user_id = {$user_id} AND details_id = {$details_id} LIMIT 1"
            );
        
        return (isset($results[0])) ? true : false;
        }

    function get_details($user_id, $details_id) {
        $results = $this->DB->run('
            SELECT * FROM meetup_details WHERE user_id = :user_id AND details_id = :details_id LIMIT 1
        ', [
            'user_id' => $user_id,
            'details_id' => $details_id
        ]); 
        
        return (isset($results[0])) ? $results[0] : false;
        }
 

    function get_settings($user_id) {
        $results = $this->DB->run('
            SELECT * FROM meetup_settings WHERE user_id = :user_id LIMIT 1
        ', [
            'user_id' => $user_id,
        ]); 
        return (isset($results[0])) ? $results[0] : false;
    }

}

?>