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
        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
}

?>