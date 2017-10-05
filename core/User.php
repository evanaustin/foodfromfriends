<?php
 
class User extends Base {
    
    protected
        $class_dependencies,
        $DB;

    public $GrowerOperation;
        
    function __construct($parameters) {
        $this->table = 'users';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->populate_fully($parameters['id']);
            $this->is_grower($parameters['id']);
        }
    }
    
    private function populate_fully($id) {
        $results = $this->DB->run('
            SELECT 
                u.*,
                ua.address_line_1,
                ua.address_line_2,
                ua.city,
                ua.state,
                ua.zipcode,
                ua.latitude,
                ua.longitude,
                upi.filename,
                upi.ext
            
            FROM users u
            
            LEFT JOIN user_addresses ua
                ON u.id = ua.user_id
            
            LEFT JOIN user_profile_images upi
                ON u.id = upi.user_id
            
            WHERE u.id = :id
        
            LIMIT 1
        ', [
            'id' => $id
        ]);

        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
    }

    private function is_grower($id) {
        // should check that operation actually exists too
        $results = $this->DB->run('
            SELECT grower_operation_id FROM grower_operation_members WHERE user_id=:id LIMIT 1
        ', [
            'id' => $id
        ]);

        if (!empty($results[0])) {
            $this->GrowerOperation = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $results[0]['grower_operation_id']
            ]);
        } else {
            $this->GrowerOperation = false;
        }
    }

    public function authenticate($email, $password) {
        $results = $this->DB->run('
            SELECT * FROM users WHERE email=:email AND password=:password LIMIT 1
        ', [
            'email'     => $email,
            'password'  => hash('sha256', $password) 
        ]);

        return (isset($results[0])) ? $results[0]['id'] : false;
    }

    public function log_in($id) {
        $_SESSION['user']['id'] = $id;

        return ($_SESSION['user']['id']) ? $_SESSION['user']['id'] : false;
    }

    public function log_out() {
        if (!empty($_SESSION['user']['id'])) {
            session_unset();
            session_destroy();
            
            return true;
        }

        return false;
    }

}

?>