<?php
 
class FoodListing extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $food_subcategory,
        $price,
        $description;
     
    function __construct($parameters) {
        $this->table = 'food_listings';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    function get_listings($user_id) {
        $results = $this->DB->run('
            SELECT 
                fl.*,
                fsc.title AS subcategory_title,
                fsc.food_category_id,
                fc.title AS category_title
            FROM food_listings fl
            LEFT JOIN food_subcategories fsc
                ON fl.food_subcategory_id = fsc.id
            LEFT JOIN food_categories fc
                ON fsc.food_category_id = fc.id
            WHERE fl.user_id = :user_id
        ', [
            'user_id' => $user_id
        ]);

        return (isset($results[0])) ? $results : false;
    }

    function get_categories() {
        $results = $this->DB->run('
            SELECT * FROM food_categories
        ');
        
        return (isset($results)) ? $results : false;
    }

    function get_subcategories() {
        $results = $this->DB->run('
            SELECT * FROM food_subcategories
        ');

        return (isset($results)) ? $results : false;
    }

    function other_exists($other) {
        $results = $this->DB->run('
            SELECT fc.title 
            FROM food_categories fc
            JOIN food_subcategories fsc
                ON fc.id = fsc.food_category_id
            WHERE fsc.title = :other
            LIMIT 1
        ', [
            'other' => $other
        ]);
        
        return (isset($results[0])) ? $results[0]['title'] : false;
    }
}

?>