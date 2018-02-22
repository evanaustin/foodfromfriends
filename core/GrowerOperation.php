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
            $this->link = ($this->type == 'none' || $this->type == 'other' ? 'grower' : $this->type) . '/' . $this->slug;

            if (isset($configure['details']) && $configure['details'] == true) {
                $this->configure_details();
            }
            
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
    
    private function configure_details() {
        if ($this->type == 'none') {
            $owner_id = $this->get_owner();
            
            $Owner = new User([
                'DB' => $this->DB,
                'id' => $owner_id
            ]);
    
            $this->details = [
                'lat'       => $Owner->latitude,
                'lng'       => $Owner->longitude,
                'bio'       => $Owner->bio,
                'address_line_1' => $Owner->address_line_1,
                'address_line_2' => $Owner->address_line_2,
                'city'      => $Owner->city,
                'state'     => $Owner->state,
                'zipcode'   => $Owner->zipcode,
                'path'      => '/profile-photos/' . $Owner->filename,
                'ext'       => $Owner->ext,
                'joined'    => $Owner->registered_on   
            ];
        } else {
            $this->details = [
                'lat'       => $this->latitude,
                'lng'       => $this->longitude,
                'bio'       => $this->bio,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city'      => $this->city,
                'state'     => $this->state,
                'zipcode'   => $this->zipcode,
                'path'      => '/grower-operation-images/' . $this->filename,
                'ext'       => $this->ext,
                'joined'    => $this->created_on   
            ];
        }
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

    /*
     * Creates a `grower_operation` record
     * Creates a `grower_operation_members` to tie op record to owner
     * 
     * @param $User the operation owner
     * @param array $data the data for `grower_operations` - shell ops only require $data['type']; other ops require $data['type'] AND $data['name']
     *  ['type', 'name', 'bio']
     * @param array $options optional data for `grower_operation_members` - defaults to permission:2 & is_default:true
     *  ['permission', 'is_default']
     */
    public function create($User, $data, $options = null) {
        // shell ops are named after the owner; other ops are explicity created with a given name
        $name = ($data['type'] == 0) ? $User->name : ((isset($data['name'])) ? $data['name'] : '');

        if (!empty($name) && !empty($data['type'])) {
            $Slug = new Slug([
                'DB' => $this->DB
            ]);

            // craft the op slug - only needs to be unique within op type
            $slug = $Slug->slugify_name($name, 'grower_operations', $data['type'], 'grower_operation_type_id');

            if (empty($slug)) {
                throw new \Exception('Slug generation failed');
            }

            // initialize operation
            $grower_added = $this->add([
                'grower_operation_type_id'  => $data['type'],
                'name'                      => $name,
                'bio'                       => (isset($data['bio'])) ? $data['bio'] : '',
                'slug'                      => $slug,
                'referral_key'              => $this->gen_referral_key(4, $name),
                'created_on'                => \Time::now(),
                'is_active'                 => 0
            ]);

            if (!$grower_added) {
                throw new \Exception('Operation creation failed');
            }

            $grower_operation_id = $grower_added['last_insert_id'];

            // assign user ownership of new operation
            $association_added = $this->add([
                'grower_operation_id'   => $grower_operation_id,
                'user_id'               => $User->id,
                'permission'            => (isset($options, $options['permission'])) ? $options['permission'] : 2,
                'is_default'            => (isset($options, $options['is_default'])) ? $options['is_default'] : 1
            ], 'grower_operation_members');

            if (!$association_added) {
                throw new \Exception('Operation + User association failed');
            }
        
            return $grower_operation_id;
        } else {
            throw new \Exception('Operation name not supplied');
        }
    }

    public function check_active($User) {
        if (
            (
                ($this->type == 'none' && !empty($User->filename))
                || ($this->type != 'none' && !empty($this->filename))
            )
            && (
                ($this->type == 'none' && !empty($User->zipcode))
                || ($this->type != 'none' && !empty($this->zipcode))
            )
            && (($this->Delivery && $this->Delivery->is_offered) || ($this->Pickup && $this->Pickup->is_offered) || ($this->Meetup && $this->Meetup->is_offered))
            && $this->count_listings() > 0
        ) {
            $this->update([
                'is_active' => 1
            ],
            'id', $this->id);
            
            $this->is_active = 1;
        } else {
            $this->update([
                'is_active' => 0
            ],
            'id', $this->id);
            
            $this->is_active = 0;
        }

        return $this->is_active;
    }

    public function determine_outstanding_orders() {
        $new = $this->DB->run('
            SELECT 
                og.id

            FROM order_growers og

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.expired_on   IS NULL
                AND os.rejected_on  IS NULL
                AND os.confirmed_on IS NULL
                AND os.seller_cancelled_on IS NULL
                AND os.buyer_cancelled_on IS NULL

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

    public function count_listings($grower_operation_id = null) {
        if (!isset($grower_operation_id)) {
            $grower_operation_id = $this->id;
        }

        $results = $this->DB->run('
            SELECT 
                COUNT(DISTINCT fl.id) AS listings
            
            FROM food_listings fl
            
            WHERE fl.grower_operation_id = :grower_operation_id
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        return (isset($results[0])) ? $results[0]['listings'] : false;
    }

    public function gen_referral_key($len, $name = null) {
        $slug = strtoupper(preg_replace('/[\s\-\_]+/', '', $name));
        $code = substr(md5(microtime()), rand(0,26), $len);
        
        return (!empty($slug) ? $slug . '_' . $code : $code);
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