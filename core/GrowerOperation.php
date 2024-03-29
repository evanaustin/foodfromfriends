<?php
 
class GrowerOperation extends Base {
    
    public
        $id,
        $grower_operation_type_id,
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
        $details,
        $new_orders,
        $pending_orders;

    public
        $Owner,
        $TeamMembers,
        $Delivery,
        $Pickup,
        $Meetup;
    
    protected
        $class_dependencies,
        $DB;

    function __construct($parameters, $configure = null) {
        $this->table = 'grower_operations';

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
            $this->link = ($this->type == 'individual' || $this->type == 'other' ? 'grower' : $this->type) . '/' . $this->slug;

            if (isset($configure['team']) && $configure['team'] == true) {
                $this->configure_team();
            }

            if (isset($configure['exchange']) && $configure['exchange'] == true) {
                $this->configure_exchange_options();
            }
        }
    }
    
    private function populate_fully() {
        $results = $this->DB->run('
            SELECT 
                go.*,
                got.title AS type,
                goa.address_line_1,
                goa.address_line_2,
                goa.city,
                goa.state,
                goa.zipcode,
                goa.latitude,
                goa.longitude,
                gopi.filename,
                gopi.ext
            
            FROM grower_operations go
            
            JOIN grower_operation_types got
                ON got.id = go.grower_operation_type_id

            LEFT JOIN grower_operation_addresses goa
                ON goa.grower_operation_id = go.id
            
            LEFT JOIN grower_operation_images gopi
                ON gopi.grower_operation_id = go.id
            
            WHERE go.id = :id
            
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

            FROM grower_operation_members gom

            WHERE gom.grower_operation_id = :grower_operation_id
                AND gom.permission > 0
        ', [
            'grower_operation_id' => $this->id
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

    private function configure_exchange_options() {
        $results = $this->DB->run('
            SELECT 
                ds.id AS delivery_id,
                ps.id AS pickup_id,
                ms.id AS meetup_id

            FROM grower_operations go
            
            LEFT JOIN delivery_settings ds
                ON ds.grower_operation_id = go.id

            LEFT JOIN pickup_settings ps
                ON ps.grower_operation_id = go.id

            LEFT JOIN meetup_settings ms
                ON ms.grower_operation_id = go.id

            WHERE go.id = :id
        ', [
            'id' => $this->id
        ]);

        if (isset($results[0]['delivery_id'])) {
            $this->Delivery = new Delivery([
                'DB' => $this->DB,
                'id' => $results[0]['delivery_id']
            ]);
        } else {
            $this->Delivery = false;
        }
        
        if (isset($results[0]['pickup_id'])) {
            $this->Pickup = new Pickup([
                'DB' => $this->DB,
                'id' => $results[0]['pickup_id']
            ]);
        } else {
            $this->Pickup = false;
        }
        
        if (isset($results[0]['meetup_id'])) {
            $this->Meetup = new Meetup([
                'DB' => $this->DB,
                'id' => $results[0]['meetup_id']
            ]);
        } else {
            $this->Meetup = false;
        }
    }

    /**
     * Creates a `grower_operation` record
     * Creates a `grower_operation_members` to tie op record to owner
     * 
     * @param object $user_id the ID of the GrowerOperation owner
     * @param array $data the data for `grower_operations` - shell ops only require $type; other ops require $type AND $name
     *  ['type', 'name', 'bio', 'address_line_1', 'city', 'state']
     * @param array $options optional data for `grower_operation_members` - defaults to permission:2 & is_default:true
     *  ['permission', 'is_default']
     */
    public function create($user_id, $data, $options = null) {
        foreach ($data as $k => $v) ${str_replace('-', '_', $k)} = $v;

        if (!empty($name) && !empty($type)) {
            $Slug = new Slug([
                'DB' => $this->DB
            ]);

            // craft the account slug - only needs to be unique within op type
            $slug = $Slug->slugify_name($name, 'grower_operations', $type, 'grower_operation_type_id');

            if (empty($slug)) {
                throw new \Exception('Slug generation failed');
            }

            // initialize operation
            $grower_added = $this->add([
                'grower_operation_type_id'  => $type,
                'name'                      => $name,
                'bio'                       => (isset($bio)) ? $bio : '',
                'slug'                      => $slug,
                'referral_key'              => $this->gen_referral_key(4, $name),
                'created_on'                => \Time::now(),
                'is_active'                 => 0
            ]);

            if (!$grower_added) {
                throw new \Exception('Operation creation failed');
            }

            $grower_operation_id = $grower_added['last_insert_id'];

            if (isset($address_line_1, $city, $state, $zipcode)) {
                $full_address       = "{$address_line_1}, {$city}, {$state}";
                $prepared_address   = str_replace(' ', '+', $full_address);

                $geocode            = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$prepared_address}&key=" . GOOGLE_MAPS_KEY);
                $output             = json_decode($geocode);

                $latitude           = $output->results[0]->geometry->location->lat;
                $longitude          = $output->results[0]->geometry->location->lng;

                $added = $this->add([
                    'grower_operation_id'   => $grower_operation_id,
                    'address_line_1'        => $address_line_1,
                    'address_line_2'        => (isset($address_line_2)) ? $address_line_2 : '',
                    'city'                  => ucwords(strtolower($city)),
                    'state'                 => strtoupper($state),
                    'zipcode'               => $zipcode,
                    'latitude'              => $latitude,
                    'longitude'             => $longitude
                ], 'grower_operation_addresses');
    
                if (!$added) {
                    throw new \Exception('We could not add your operation\'s location');
                }
            }

            // assign user ownership of new operation
            $association_added = $this->add([
                'grower_operation_id'   => $grower_operation_id,
                'user_id'               => $user_id,
                'permission'            => (isset($options, $options['permission'])) ? $options['permission'] : 2,
                'is_default'            => (isset($options, $options['is_default'])) ? $options['is_default'] : 1
            ], 'grower_operation_members');

            if (!$association_added) {
                throw new \Exception('Seller account association failed');
            }
        
            return $grower_operation_id;
        } else {
            throw new \Exception('Operation name not supplied');
        }
    }

    public function check_active() {
        $payout_settings = $this->retrieve([
            'where' => [
                'seller_id' => $this->id
            ],
            'table' => 'seller_payout_settings',
            'limit' => 1
        ]);

        if (!empty($payout_settings)
            && !empty($this->filename)
            && !empty($this->latitude)
            && !empty($this->longitude)
            && ((isset($this->Delivery) && $this->Delivery->is_offered)
                || (isset($this->Pickup) && $this->Pickup->is_offered)
                || (isset($this->Meetup) && $this->Meetup->is_offered))
            && ($this->count_items() > 0)
        ) {
            $this->update([
                'is_active' => 1
            ]);
            
            $this->is_active = 1;
        } else {
            $this->update([
                'is_active' => 0
            ]);
            
            $this->is_active = 0;
        }

        return $this->is_active;
    }

    /**
     * Retrieves new & pending orders
     * 
     * @return void
     */
    public function determine_outstanding_orders() {
        $new = $this->DB->run('
            SELECT 
                og.id

            FROM order_growers og

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.confirmed_on IS NULL
                AND os.rejected_on  IS NULL
                AND os.expired_on   IS NULL

            LIMIT 1
        ', [
            'grower_operation_id' => $this->id
        ]);

        $pending = $this->DB->run('
            SELECT 
                og.id

            FROM order_growers og

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.confirmed_on IS NOT NULL
                AND os.seller_cancelled_on IS NULL
                AND os.buyer_cancelled_on IS NULL
                AND os.fulfilled_on IS NULL

            LIMIT 1
        ', [
            'grower_operation_id' => $this->id
        ]);

        $this->new_orders       = isset($new[0]);
        $this->pending_orders   = isset($pending[0]);
    }

    public function get_owner() {
        $results = $this->DB->run('
            SELECT u.id

            FROM grower_operation_members gom

            JOIN users u
                ON gom.user_id = u.id

            WHERE gom.grower_operation_id = :grower_operation_id
                AND gom.permission = 2

            LIMIT 1
        ', [
            'grower_operation_id' => $this->id
        ]);

        return (isset($results[0])) ? $results[0]['id'] : false;
    }

    public function get_team_members() {
        $results = $this->DB->run('
            SELECT 
                gom.permission,
                u.id,
                u.first_name,
                u.last_name

            FROM grower_operation_members gom

            JOIN users u
                ON gom.user_id = u.id

            WHERE gom.grower_operation_id = :grower_operation_id
                AND gom.permission > 0
        ', [
            'grower_operation_id' => $this->id
        ]);

        return (isset($results)) ? $results : false;
    }

    public function get_types() {
        $results = $this->DB->run('
            SELECT * FROM grower_operation_types
        ');
        
        return (isset($results)) ? $results : false;
    }

    public function check_association($user_id, $grower_operation_id = null) {
        if (!isset($grower_operation_id)) {
            $grower_operation_id = $this->id;
        }

        $results = $this->DB->run('
            SELECT *

            FROM grower_operation_members gom

            WHERE gom.grower_operation_id = :grower_operation_id
                AND user_id = :user_id
            
            LIMIT 1
        ', [
            'grower_operation_id'   => $grower_operation_id,
            'user_id'               => $user_id
        ]);

        return (isset($results[0])) ? $results[0] : false;
    }

    public function check_team_elsewhere($user_id) {
        $results = $this->DB->run('
            SELECT *

            FROM grower_operation_members gom

            WHERE user_id = :user_id
                AND permission > 0
            
            LIMIT 1
        ', [
            'user_id' => $user_id
        ]);

        return (isset($results[0])) ? $results[0]['grower_operation_id'] : false;
    }

    public function count_items($grower_operation_id = null) {
        if (!isset($grower_operation_id)) {
            $grower_operation_id = $this->id;
        }

        $results = $this->DB->run('
            SELECT 
                COUNT(DISTINCT i.id) AS items
            
            FROM items i
            
            WHERE i.grower_operation_id = :grower_operation_id
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        return (isset($results[0])) ? $results[0]['items'] : false;
    }

    /**
     * Returns an array of `Order` objects for all orders that included items from this grower.  Note that
     * data for all growers in the order is present in each Order->Growers array, so on display you'll
     * have to show only the data for this grower, which is present in Order->Growers[operation_id].
     *
     * By default this method returns all orders.  To return only open orders (ones that haven't been
     * fulfilled), pass in a `$subset` argument value of `open`.  To return fulfilled orders, pass
     * in `fulfilled`.
     *
     * @param string|null $subset Either `all`, `open`, or `fulfilled`.  Defaults to `all`.
     * @return array Array of `Order` objects
     */
    public function get_orders($subset = 'all') {
        if ($subset == 'open') {
            $where = 'AND og.fulfilled_on IS NULL';
        } else if ($subset == 'fulfilled') {
            $where = 'AND og.fulfilled_on IS NOT NULL';
        } else {
            $where = '';
        }

        $results = $this->DB->run('
            SELECT o.id
            FROM order_growers og
            INNER JOIN orders o ON o.id = og.order_id
            WHERE og.grower_operation_id = :operation_id ' . $where . '
        ', [
            'operation_id' => $this->id
        ]);

        $Orders = [];

        foreach ($results as $result) {
            $Orders []= new Order(['id' => $result['id']]);
        }

        return $Orders;
    }

}