<?php
 
class User extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $email,
        $password,
        $first_name,
        $last_name,
        $validation,
        $registered_on,
        $is_admin;
    
    function __construct($parameters) {
        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
    
    private function configure_object($id) {
        $bind = [
            'id' => $id
        ];
        
        $results = $this->DB->run('
            SELECT * FROM users WHERE id=:id LIMIT 1
        ', $bind);

        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v;
    }
    
    public function add($fields) {
        $results = $this->DB->insert('users', $fields);
        
        return (isset($results)) ? $results : false;
    }
    
    public function exists($email) {
        $bind = [
            'email' => $email
        ];
        
        $results = $this->DB->run('
            SELECT * FROM users WHERE email=:email LIMIT 1
        ', $bind);
        
        return (isset($results[0])) ? true : false;
    }

    public function authenticate($data) {
        $bind = [
            'email' => $data['email'],
            'password' => hash('sha256', $data['password']) 
        ];

        $results = $this->DB->run('
            SELECT * FROM users WHERE email=:email AND password=:password LIMIT 1
        ', $bind);

        if (isset($results[0])) {
            $_SESSION['user']['id'] = $results[0]['id'];

            return true;
        }
        
        return false;
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