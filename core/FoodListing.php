<?php
 
class FoodListing extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $user_id,
        $food_subcategory_id,
        $price,
        $stock,
        $description;
     
        
    function __construct($parameters) {
        $this->table = 'food_listings';


        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    

        if (isset($parameters['user_id'])) $this->configure_object($parameters['user_id']);
    }


    public function join_foodlistings($data) {
        if (isset($data)) {
            $bind = [
                'data' => $data
            ];
            $foodlistings = $this->DB->run("
                SELECT * 
                FROM food_listings fl
                JOIN food_subcategories fs
                ON fl.food_subcategories_id = fs.id
                    WHERE fl.user_id = :data
            ", $bind);

        return (isset($foodlistings)) ? $foodlistings : false;
        }

           


        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
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
}



?>