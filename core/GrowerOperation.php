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
            $this->populate_fully($parameters['id']);
        }
    }
    
    private function populate_fully($id) {
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
            'id' => $id
        ]);

        if (!isset($results[0])) return false;
        
        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
    }

    public function gen_referral_key($name = null) {
        $slug = strtoupper(preg_replace('/[\s\-\_]+/', '', $name));
        $code = substr(md5(microtime()), rand(0,26), 4);
        
        return $slug . '_' . $code;
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

    /*
    * moved from Grower (does it need to be refactored?)
    */
    public function count_listings($user_id = null) {
        if (!isset($user_id)) {
            $user_id = $this->id;
        }

        $results = $this->DB->run('
            SELECT 
                COUNT(DISTINCT fl.id) AS listings
            
            FROM food_listings fl
            
            WHERE fl.user_id = :user_id
        ', [
            'user_id' => $user_id
        ]);

        return (isset($results[0])) ? $results[0]['listings'] : false;
    }

    public function get_types() {
        $results = $this->DB->run('
            SELECT * FROM grower_operation_types
        ');
        
        return (isset($results)) ? $results : false;
    }

}

?>