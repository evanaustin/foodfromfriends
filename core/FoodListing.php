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