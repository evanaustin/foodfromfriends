<?php
 
class FoodListing extends Base {
    
    protected
        $class_dependencies,
        $DB,
        $S3;
        
    function __construct($parameters) {
        $this->table = 'food_listings';


        $this->class_dependencies = [
            'DB',
            'S3'
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
                fl.*,
                fsc.title AS subcategory_title,
                fsc.food_category_id,
                fc.title AS category_title,
                fli.filename,
                fli.ext
            
            FROM food_listings fl
            
            LEFT JOIN food_subcategories fsc
                ON fsc.id = fl.food_subcategory_id
            
            LEFT JOIN food_categories fc
                ON fc.id = fsc.food_category_id
            
            LEFT JOIN food_listing_images fli
                ON fli.food_listing_id = fl.id
            
            WHERE fl.id = :id
        
            LIMIT 1
        ', [
            'id' => $id
        ]);

        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
    }

    public function get_listings($user_id) {
        $results = $this->DB->run('
            SELECT 
                fl.*,
                fsc.title AS subcategory_title,
                fsc.food_category_id,
                fc.title AS category_title,
                fli.filename,
                fli.ext
            
            FROM food_listings fl
            
            LEFT JOIN food_subcategories fsc
                ON fl.food_subcategory_id = fsc.id
            
            LEFT JOIN food_categories fc
                ON fsc.food_category_id = fc.id
            
            LEFT JOIN food_listing_images fli
                ON fl.id = fli.food_listing_id
            
            WHERE fl.user_id = :user_id
        ', [
            'user_id' => $user_id
        ]);

        return (isset($results[0])) ? $results : false;
    }

    public function get_categories() {
        $results = $this->DB->run('
            SELECT * FROM food_categories
        ');
        
        return (isset($results)) ? $results : false;
    }

    public function get_subcategories() {
        $results = $this->DB->run('
            SELECT * FROM food_subcategories
        ');

        return (isset($results)) ? $results : false;
    }

    public function other_exists($other) {
        $results = $this->DB->run('
            SELECT fc.title 
            
            FROM food_categories fc
            
            JOIN food_subcategories fsc
                ON fsc.food_category_id = fc.id
            
            WHERE fsc.title = :other
            
            LIMIT 1
        ', [
            'other' => $other
        ]);
        
        return (isset($results[0])) ? $results[0]['title'] : false;
    }
}



?>