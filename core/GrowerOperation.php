<?php
 
class GrowerOperation extends Base {
    
    protected
        $class_dependencies,
        $DB;

    function __construct($parameters) {
        $this->table = 'grower_operations';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);

        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->populate_fully();
            $this->configure_exchange_options();
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
            && ($this->Delivery || $this->Pickup || $this->Meetup)
            && ($this->Delivery->is_offered || $this->Pickup->is_offered || $this->Meetup->is_offered)
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

    public function check_association($grower_operation_id, $user_id) {
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

    /*
    * moved from Grower (needs to be refactored - don't pull data that User would otherwise have)
    */
    public function pull_all() {
        $results = $this->DB->run('
            SELECT 
                u.id,
                u.first_name,
                ua.address_line_1,
                ua.address_line_2,
                ua.city,
                ua.state,
                ua.zipcode,
                ua.latitude,
                ua.longitude,
                upi.filename,
                upi.ext,
                COUNT(DISTINCT fl.id) AS listings,
                AVG(gr.rating) AS rating
            
            FROM grower_operations go

            JOIN grower_operation_members gom
                ON gom.grower_operation_id = go.id

            JOIN users u
                ON u.id = gom.user_id
            
            JOIN user_addresses ua
                ON ua.user_id = u.id
            
            JOIN user_profile_images upi
                ON upi.user_id = u.id
            
            LEFT JOIN food_listings fl
                ON fl.grower_operation_id = go.id

            LEFT JOIN grower_ratings gr
                ON gr.user_id = u.id
            
            GROUP BY go.id
        ');

        return (isset($results[0])) ? $results : false;
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

}

?>