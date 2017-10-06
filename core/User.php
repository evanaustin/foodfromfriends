<?php
 
class User extends Base {
    
    protected
        $class_dependencies,
        $DB;

    function __construct($parameters) {
        $this->table = 'users';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->populate_fully($parameters['id']);
            $this->get_operations($parameters['id']);
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

    // eventually this should be refactored to allow for buyer operations also
    private function get_operations($user_id) {
        $results = $this->DB->run('
            SELECT *

            FROM grower_operation_members gom

            WHERE gom.user_id = :user_id 
                AND permission > 0
        ', [
            'user_id' => $user_id
        ]);

        if (isset($results)) {
            foreach ($results as $result) {
                $this->Operations[$result['grower_operation_id']] = new GrowerOperation([
                    'DB' => $this->DB,
                    'id' => $result['grower_operation_id']
                ]);

                $active_operation_id = $_SESSION['user']['active_operation_id'];

                if ((!isset($active_operation_id) && $result['is_default']) || $active_operation_id == $result['grower_operation_id']) {
                    $this->GrowerOperation = $this->Operations[$result['grower_operation_id']];
                    $this->permission = $result['permission'];
                    $_SESSION['user']['active_operation_id'] = $result['grower_operation_id'];
                }
            }
        } else {
            $this->Operations = false;
            $this->GrowerOperation = false;
        }
    }

    public function switch_operation($id) {
        $_SESSION['user']['active_operation_id'] = $id;
        $this->GrowerOperation = $this->Operations[$id];

        return $this->GrowerOperation->id;
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