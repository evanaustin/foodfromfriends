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

        }   

    }

?>