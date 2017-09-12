<?php
 
class User extends Base {
    
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
        $city,
        $state,
        $zip,
        $validation,
        $registered_on,
        $is_admin;
    
    function __construct($parameters) {
        $this->table = 'users';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->populate_fully($parameters['id']);
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

    public function authenticate($email, $password) {
        $results = $this->DB->run('
            SELECT * FROM users WHERE email=:email AND password=:password LIMIT 1
        ', [
            'email' => $email,
            'password' => hash('sha256', $password) 
        ]);

        if (isset($results[0])) {
            return $this->log_in($results[0]['id']);
        }
        
        return false;
    }

    public function log_in($id) {
        $_SESSION['user']['id'] = $id;

        return ($_SESSION['user']['id']) ? true : false;
    }

    public function log_out() {
        if (!empty($_SESSION['user']['id'])) {
            session_unset();
            session_destroy();
            
            return true;
        }

        return false;
    }

    /*
    * This will get refactored as search capability is integrated
    */
    public function pull_all_growers() {
        $results = $this->DB->run('
            SELECT 
                u.id,
                u.first_name,
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
            WHERE u.is_grower = :is_grower
                AND ua.zipcode IS NOT NULL
        ', [
            'is_grower' => 1
        ]);

        return (isset($results[0])) ? $results : false;
    }
    
}

?>