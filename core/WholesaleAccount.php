<?php
 
class WholesaleAccount extends Base {
    
    public
        $id,
        $wholesale_account_type_id,
        $name,
        $bio,
        $average_rating,
        $referral_key,
        $created_on,
        $is_active,
        $type,
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
        $link,
        $details;

    public
        $Owner,
        $TeamMembers;
    
    protected
        $class_dependencies,
        $DB;

    function __construct($parameters, $configure = null) {
        $this->table = 'wholesale_accounts';

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
            $this->populate_fully();
            $this->link = "{$this->type}/{$this->slug}";

            if (isset($configure['team']) && $configure['team'] == true) {
                $this->configure_team();
            }
        }
    }
    
    private function populate_fully() {
        $results = $this->DB->run('
            SELECT 
                wa.*,
                wat.title AS type,
                waa.address_line_1,
                waa.address_line_2,
                waa.city,
                waa.state,
                waa.zipcode,
                waa.latitude,
                waa.longitude,
                wai.filename,
                wai.ext
            
            FROM wholesale_accounts wa
            
            JOIN wholesale_account_types wat
                ON wat.id = wa.wholesale_account_type_id

            LEFT JOIN wholesale_account_addresses waa
                ON waa.wholesale_account_id = wa.id
            
            LEFT JOIN wholesale_account_images wai
                ON wai.wholesale_account_id = wa.id
            
            WHERE wa.id = :id
            
            LIMIT 1
        ', [
            'id' => $this->id
        ]);

        if (!isset($results[0])) return false;
        
        foreach ($results[0] as $k => $v) $this->{$k} = $v;
    }
    
    private function configure_team() {
        $results = $this->DB->run('
            SELECT *

            FROM wholesale_account_members wam

            WHERE wam.wholesale_account_id = :wholesale_account_id
                AND wam.permission > 0
        ', [
            'wholesale_account_id' => $this->id
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
     * Creates a `wholesale_account` record
     * Creates a `wholesale_account_members` to tie wholesale_account record to owner
     * 
     * @param object $User the operation owner
     * @param array $data the data for `wholesale_account`
     *  ['type', 'name', 'bio', 'address_line_1', 'city', 'state']
     * @param array $options optional data for `wholesale_account_members` - defaults to permission:2 & is_default:true
     *  ['permission', 'is_default']
     */
    public function create($User, $data, $options = null) {
        if (!empty($data['name']) && !empty($data['type'])) {
            $Slug = new Slug([
                'DB' => $this->DB
            ]);

            // craft the op slug - only needs to be unique within account type
            $slug = $Slug->slugify_name($data['name'], 'wholesale_accounts', $data['type'], 'wholesale_account_type_id');

            if (empty($slug)) {
                throw new \Exception('Slug generation failed');
            }

            // initialize operation
            $added = $this->add([
                'wholesale_account_type_id' => $data['type'],
                'name'                      => $data['name'],
                'bio'                       => (isset($data['bio'])) ? $data['bio'] : '',
                'slug'                      => $slug,
                'referral_key'              => $this->gen_referral_key(4, $data['name']),
                'created_on'                => \Time::now(),
                'is_active'                 => 0
            ]);

            if (!$added) {
                throw new \Exception('Wholesale account creation failed');
            }

            $wholesale_account_id = $added['last_insert_id'];

            if (isset($address_line_1, $city, $state)) {
                $full_address       = "{$address_line_1}, {$city}, {$state}";
                $prepared_address   = str_replace(' ', '+', $full_address);

                $geocode            = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepared_address . '&key=' . GOOGLE_MAPS_KEY);
                $output             = json_decode($geocode);

                $latitude           = $output->results[0]->geometry->location->lat;
                $longitude          = $output->results[0]->geometry->location->lng;

                $added = $this->add([
                    'wholesale_account_id'  => $wholesale_account_id,
                    'address_line_1'        => $address_line_1,
                    'address_line_2'        => $address_line_2,
                    'city'                  => ucfirst($city),
                    'state'                 => $state,
                    'zipcode'               => $zipcode,
                    'latitude'              => $latitude,
                    'longitude'             => $longitude
                ], 'wholesale_account_addresses');
    
                if (!$added) quit('We could not add your account\'s location');
            }

            // assign user ownership of new operation
            $association_added = $this->add([
                'wholesale_account_id'  => $wholesale_account_id,
                'user_id'               => $User->id,
                'permission'            => (isset($options, $options['permission'])) ? $options['permission'] : 2,
                'is_default'            => (isset($options, $options['is_default'])) ? $options['is_default'] : 1
            ], 'wholesale_account_members');

            if (!$association_added) {
                throw new \Exception('Account + User association failed');
            }
        
            return $wholesale_account_id;
        } else {
            throw new \Exception('Account name not supplied');
        }
    }

    public function get_owner() {
        $results = $this->DB->run('
            SELECT u.id

            FROM wholesale_account_members wam

            JOIN users u
                ON wam.user_id = u.id

            WHERE wam.wholesale_account_id = :wholesale_account_id
                AND wam.permission = 2

            LIMIT 1
        ', [
            'wholesale_account_id' => $this->id
        ]);

        return (isset($results[0])) ? $results[0]['id'] : false;
    }

    public function get_team_members() {
        $results = $this->DB->run('
            SELECT 
                wam.permission,
                u.id,
                u.first_name,
                u.last_name

            FROM wholesale_account_members wam

            JOIN users u
                ON wam.user_id = u.id

            WHERE wam.wholesale_account_id = :wholesale_account_id
                AND wam.permission > 0
        ', [
            'wholesale_account_id' => $this->id
        ]);

        return (isset($results)) ? $results : false;
    }

    public function get_types() {
        $results = $this->DB->run('
            SELECT * FROM wholesale_account_types
        ');
        
        return (isset($results)) ? $results : false;
    }

    public function check_association($user_id, $wholesale_account_id = null) {
        if (!isset($wholesale_account_id)) {
            $wholesale_account_id = $this->id;
        }

        $results = $this->DB->run('
            SELECT *

            FROM wholesale_account_members wam

            WHERE wam.wholesale_account_id = :wholesale_account_id
                AND user_id = :user_id
            
            LIMIT 1
        ', [
            'wholesale_account_id'  => $wholesale_account_id,
            'user_id'               => $user_id
        ]);

        return (isset($results[0])) ? $results[0] : false;
    }

    public function check_team_elsewhere($user_id) {
        $results = $this->DB->run('
            SELECT *

            FROM wholesale_account_members wam

            WHERE user_id = :user_id
                AND permission > 0
            
            LIMIT 1
        ', [
            'user_id' => $user_id
        ]);

        return (isset($results[0])) ? $results[0]['wholesale_account_id'] : false;
    }

    public function approve_membership($membership_id) {
        $results = $this->update([
            'status' => 2
        ], 'id', $membership_id, 'wholesale_account_memberships');

        if (!$results) {
            throw new \Exception('Could not approve wholesale buyer');
        }
    }
    
    public function unapprove_membership($membership_id) {
        $results = $this->update([
            'status' => 0
        ], 'id', $membership_id, 'wholesale_account_memberships');

        if (!$results) {
            throw new \Exception('Could not unapprove wholesale buyer');
        }
    }

}