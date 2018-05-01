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
        $registered_on,
        $timezone;


    public
        $filename,
        $ext;

    public
        $name;
        
    public
        $Operations,
        $GrowerOperation,
        $Orders,
        $ActiveOrder;
    
    public
        $BuyerAccounts,
        $BuyerAccount;

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
        } else if (isset($parameters['slug'])) {
            $results = $this->DB->run("
                SELECT * FROM {$this->table} WHERE slug=:slug LIMIT 1
            ", [
                'slug' => $parameters['slug']
            ]);
            
            if (!isset($results[0])) return false;
    
            foreach ($results[0] as $k => $v) $this->{$k} = $v;            
        }

        if (isset($this->id)) {
            $this->name = "{$this->first_name} {$this->last_name}";

            if (!isset($parameters['buyer_account']) || $parameters['buyer_account'] == true) {
                $this->get_buyer_accounts();
            }

            if (!isset($parameters['seller_account']) || $parameters['seller_account'] == true) {
                $this->get_operations();
            }
    
            if (!isset($parameters['limited']) || $parameters['limited'] == false) {
                $this->get_orders();
            }
        }
    }

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

    private function get_buyer_accounts() {
        $results = $this->DB->run('
            SELECT *
            FROM buyer_account_members btm
            WHERE btm.user_id=:user_id 
                AND permission > 0
        ', [
            'user_id' => $this->id
        ]);

        if (isset($results)) {
            foreach ($results as $result) {
                $id = $result['buyer_account_id'];

                $this->BuyerAccounts[$id] = new BuyerAccount([
                    'DB' => $this->DB,
                    'id' => $result['id']
                ]);

                $this->BuyerAccounts[$id]->permission = $result['permission'];

                if ($result['is_default']) {
                    $this->BuyerAccount = $this->BuyerAccounts[$id];
                }
            }
        } else {
            $this->BuyerAccounts    = false;
            $this->BuyerAccount     = false;
        }
    }

    public function authenticate($email, $password) {
        $results = $this->DB->run('
            SELECT * 
            FROM users 
            WHERE email=:email 
                AND password=:password 
            LIMIT 1
        ', [
            'email'     => $email,
            'password'  => hash('sha256', $password)
        ]);

        return (isset($results[0])) ? $results[0]['id'] : false;
    }
    
    public function reset_password($email, $password) {
        $updated = $this->update([
            'password' => hash('sha256', $password)
        ], 'email', $email);

        return $updated;
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

    public function switch_operation($id) {
        $_SESSION['user']['active_operation_id'] = $id;
        $this->GrowerOperation = $this->Operations[$id];

        return $this->GrowerOperation->id;
    }
    
    public function switch_buyer_account($id) {
        $_SESSION['user']['active_buyer_account_id'] = $id;
        $this->BuyerAccount = $this->BuyerAccounts[$id];

        return $this->BuyerAccount->id;
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

        if (!empty($results[0])) {
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