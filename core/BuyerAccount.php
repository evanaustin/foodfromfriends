<?php
 
class BuyerAccount extends Base {
    
    public
        $id,
        $buyer_account_type_id,
        $name,
        $slug,
        $bio,
        $average_rating,
        $referral_key,
        $created_on;
        
    public
        $type,
        $link;

    public
        $Address,
        $Image;

    public
        $Billing,
        $TeamMembers,
        $Owner;
    
    protected
        $class_dependencies,
        $DB;

    function __construct($parameters, $configure = null) {
        $this->table = 'buyer_accounts';

        $this->class_dependencies = [
            'DB'
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
            $this->set_type_and_link();

            $this->Address = new AccountExtension([
                'DB'            => $this->DB,
                'account_type'  => 'buyer',
                'account_id'    => $this->id,
                'table'         => 'buyer_account_addresses'
            ]);
            
            $this->Image = new AccountExtension([
                'DB'            => $this->DB,
                'account_type'  => 'buyer',
                'account_id'    => $this->id,
                'table'         => 'buyer_account_images',
                'image'         => true
            ]);

            if (isset($configure['billing']) && $configure['billing'] == true || $configure['billing'] !== false) {
                $this->Billing = new AccountExtension([
                    'DB'            => $this->DB,
                    'account_type'  => 'buyer',
                    'account_id'    => $this->id,
                    'table'         => 'buyer_account_billing'
                ]);
            }

            if (isset($configure['team']) && $configure['team'] == true) {
                $this->configure_team();
            }
        }
    }
    
    /**
     * Loads BuyerAccount object with Address & Image children
     */
    private function set_type_and_link() {
        $results = $this->DB->run('
            SELECT bat.title AS type
            FROM buyer_accounts ba
            JOIN buyer_account_types bat
                ON bat.id = ba.buyer_account_type_id
            WHERE ba.id = :id
            LIMIT 1
        ', [
            'id' => $this->id
        ]);

        if (!isset($results[0])) {
            return false;
        } else {
            $this->type = $results[0]['type'];
            $this->link = ($this->type == 'individual' || $this->type == 'other' ? 'buyer' : $this->type) . '/' . $this->slug;
        }
    }
    
    private function configure_team() {
        $results = $this->DB->run('
            SELECT *

            FROM buyer_account_members bam

            WHERE bam.buyer_account_id=:buyer_account_id
                AND bam.permission > 0
        ', [
            'buyer_account_id' => $this->id
        ]);

        if (isset($results)) {
            foreach ($results as $result) {
                $id = $result['user_id'];

                $this->TeamMembers[$id] = new User([
                    'DB' => $this->DB,
                    'id' => $id
                ]);
            }
        } else {
            $this->TeamMembers = false;
        }
    }

    /**
     * Creates a `buyer` record
     * Creates a `buyer_account_members` to tie buyer record to owner
     * 
     * @param object $User the operation owner
     * @param array $data the data for `buyer`
     *  ['type', 'name', 'bio', 'address_line_1', 'city', 'state']
     * @param array $options optional data for `buyer_account_members` - defaults to permission:2 & is_default:true
     *  ['permission', 'is_default']
     */
    public function create($User, $data, $options = null) {
        if (!empty($data['name']) && !empty($data['type'])) {
            $Slug = new Slug([
                'DB' => $this->DB
            ]);

            // craft the op slug - only needs to be unique within account type
            $slug = $Slug->slugify_name($data['name'], 'buyer_accounts', $data['type'], 'buyer_account_type_id');

            if (empty($slug)) {
                throw new \Exception('Slug generation failed');
            }

            // initialize operation
            $added = $this->add([
                'buyer_account_type_id' => $data['type'],
                'name'                  => $data['name'],
                'slug'                  => $slug,
                'bio'                   => (isset($data['bio'])) ? $data['bio'] : '',
                'stripe_customer_id'    => (isset($data['stripe_customer_id'])) ? $data['stripe_customer_id'] : '',
                'referral_key'          => $this->gen_referral_key(4, $data['name']),
                'created_on'            => \Time::now(),
            ]);

            if (!$added) {
                throw new \Exception('Buyer account creation failed');
            }

            $buyer_account_id = $added['last_insert_id'];

            if (isset($address_line_1, $city, $state)) {
                $full_address       = "{$address_line_1}, {$city}, {$state}";
                $prepared_address   = str_replace(' ', '+', $full_address);

                $geocode            = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepared_address . '&key=' . GOOGLE_MAPS_KEY);
                $output             = json_decode($geocode);

                $latitude           = $output->results[0]->geometry->location->lat;
                $longitude          = $output->results[0]->geometry->location->lng;

                $added = $this->add([
                    'buyer_account_id'  => $buyer_account_id,
                    'address_line_1'    => $address_line_1,
                    'address_line_2'    => $address_line_2,
                    'city'              => ucfirst($city),
                    'state'             => $state,
                    'zipcode'           => $zipcode,
                    'latitude'          => $latitude,
                    'longitude'         => $longitude
                ], 'buyer_account_addresses');
    
                if (!$added) quit('We could not add your account\'s location');
            }

            // assign user ownership of new operation
            $association_added = $this->add([
                'buyer_account_id'  => $buyer_account_id,
                'user_id'           => $User->id,
                'permission'        => (isset($options, $options['permission'])) ? $options['permission'] : 2,
                'is_default'        => (isset($options, $options['is_default'])) ? $options['is_default'] : 1
            ], 'buyer_account_members');

            if (!$association_added) {
                throw new \Exception('Account + User association failed');
            }
        
            return $buyer_account_id;
        } else {
            throw new \Exception('Account name not supplied');
        }
    }

    public function get_owner() {
        $results = $this->DB->run('
            SELECT u.id

            FROM buyer_account_members bam

            JOIN users u
                ON bam.user_id = u.id

            WHERE bam.buyer_account_id = :buyer_account_id
                AND bam.permission = 2

            LIMIT 1
        ', [
            'buyer_account_id' => $this->id
        ]);

        return (isset($results[0])) ? $results[0]['id'] : false;
    }

    public function get_team_members() {
        $results = $this->DB->run('
            SELECT 
                bam.permission,
                u.id,
                u.first_name,
                u.last_name

            FROM buyer_account_members bam

            JOIN users u
                ON bam.user_id = u.id

            WHERE bam.buyer_account_id = :buyer_account_id
                AND bam.permission > 0
        ', [
            'buyer_account_id' => $this->id
        ]);

        return (isset($results)) ? $results : false;
    }

    public function get_types() {
        $results = $this->DB->run('
            SELECT * FROM buyer_account_types
        ');
        
        return (isset($results)) ? $results : false;
    }

    public function check_association($user_id, $buyer_account_id = null) {
        if (!isset($buyer_account_id)) {
            $buyer_account_id = $this->id;
        }

        $results = $this->DB->run('
            SELECT *

            FROM buyer_account_members bam

            WHERE bam.buyer_account_id = :buyer_account_id
                AND user_id = :user_id
            
            LIMIT 1
        ', [
            'buyer_account_id'  => $buyer_account_id,
            'user_id'           => $user_id
        ]);

        return (isset($results[0])) ? $results[0] : false;
    }

    public function check_team_elsewhere($user_id) {
        $results = $this->DB->run('
            SELECT *

            FROM buyer_account_members bam

            WHERE user_id = :user_id
                AND permission > 0
            
            LIMIT 1
        ', [
            'user_id' => $user_id
        ]);

        return (isset($results[0])) ? $results[0]['buyer_account_id'] : false;
    }

}