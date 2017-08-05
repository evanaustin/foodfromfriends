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
        $validation,
        $registered_on,
        $is_admin;
    
    function __construct($parameters) {
        $this->table = 'users';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
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
    
}

?>