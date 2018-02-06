<?php
 
class User extends Base {
    
    public
        $id,
        $email,
        $password,
        $first_name,
        $last_name,
        $phone,
        $dob,
        $gender,
        $bio,
        $registered_on,
        $stripe_customer_id,
        $timezone,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $zipcode,
        $latitude,
        $longitude,
        $filename,
        $ext;

    public
        $name;
        
    public
        $Operations,
        $GrowerOperation,
        $Orders,
        $ActiveOrder;

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
            $this->populate_fully();

            if (!isset($parameters['limited']) || $parameters['limited'] == false) {
                $this->get_operations();
                $this->get_orders();
            }

            $this->name = $this->first_name . ' ' . $this->last_name;
        }
    }
    
    private function populate_fully() {
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
            'id' => $this->id
        ]);

        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
    }

    // eventually this should be refactored to allow for buyer operations also
    private function get_operations() {
        $results = $this->DB->run('
            SELECT *
            FROM grower_operation_members gom
            WHERE gom.user_id = :user_id 
                AND permission > 0
        ', [
            'user_id' => $this->id
        ]);

        if (isset($results)) {
            foreach ($results as $result) {
                $id = $result['grower_operation_id'];

                $this->Operations[$id] = new GrowerOperation([
                    'DB' => $this->DB,
                    'id' => $id
                ],[
                    'exchange' => true
                ]);

                $this->Operations[$id]->permission = $result['permission'];

                if ($result['is_default']) {
                    $this->GrowerOperation = $this->Operations[$id];
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
            SELECT * 
            FROM users 
            WHERE email=:email 
                AND password=:password LIMIT 1
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

    public function soft_log_out() {
        if (!empty($_SESSION['user']['id'])) {
            $_SESSION['user']['id'] = null;
            
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

    /**
     * Returns an array of `Order` objects for each order this user has placed.
     *
     * @return array Array of `Order` objects
     */
    public function get_orders() {
        $results = $this->DB->run('
            SELECT *
            FROM orders o
            WHERE user_id = :user_id
        ', [
            'user_id' => $this->id
        ]);

        if (isset($results)) {
            foreach ($results as $result) {
                $id = $result['id'];

                $this->Orders[$id] = new Order([
                    'DB' => $this->DB,
                    'id' => $id
                ]);

                if (empty($result['charge_id'])) {
                    $this->ActiveOrder = $this->Orders[$id];
                } 
            }
        } else {
            $this->Orders = false;
            $this->ActiveOrder = false;
        }
    }

}