<?php
 
class Grower extends User {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        parent::__construct($parameters);
    }

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
            
            FROM users u
            
            JOIN user_addresses ua
                ON ua.user_id = u.id
            
            JOIN user_profile_images upi
                ON upi.user_id = u.id
            
            LEFT JOIN food_listings fl
                ON fl.user_id = u.id

            LEFT JOIN grower_ratings gr
                ON gr.user_id = u.id
            
            WHERE u.is_grower = :is_grower
                AND ua.zipcode IS NOT NULL

            GROUP BY u.id
        ', [
            'is_grower' => 1
        ]);

        return (isset($results[0])) ? $results : false;
    }

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
}

?>