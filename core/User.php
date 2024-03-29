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
        $name;
        
    public
        $BuyerAccounts,
        $BuyerAccount;

    public
        $Operations,
        $GrowerOperation;
    
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

            if (!(isset($parameters['buyer_account']) && $parameters['buyer_account'] == false)) {
                $this->get_buyer_accounts();
            }

            if (!(isset($parameters['seller_account']) && $parameters['seller_account'] == false)) {
                $this->get_seller_accounts();
            }
        }
    }

    private function get_seller_accounts() {
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
            JOIN buyer_accounts ba
                on btm.buyer_account_id = ba.id
            WHERE btm.user_id=:user_id 
                AND btm.permission > 0
        ', [
            'user_id' => $this->id
        ]);

        if (isset($results)) {
            foreach ($results as $result) {
                $id = $result['buyer_account_id'];

                $this->BuyerAccounts[$id] = $result['name'];

                // $this->BuyerAccounts[$id]->permission = $result['permission'];

                if ($result['is_default']) {
                    $this->BuyerAccount = new BuyerAccount([
                        'DB' => $this->DB,
                        'id' => $result['id']
                    ]);
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
        
        $this->BuyerAccount = new BuyerAccount([
            'DB' => $this->DB,
            'id' => $id
        ]);

        return $this->BuyerAccount->id;
    }

}