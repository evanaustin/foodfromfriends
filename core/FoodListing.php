<?php
 
class FoodListing extends Base {
    
    protected
        $class_dependencies,
        $DB,
        $S3;
        
    public
        $id,
        $food_subcategory,
        $price,
        $description;
     
    function __construct($parameters) {
        $this->table = 'food_listings';

        $this->class_dependencies = [
            'DB',
            'S3'
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

    function other_exists($other) {
        $results = $this->DB->run("
            SELECT fc.title 
            FROM food_categories fc
            JOIN food_subcategories fsc
                ON fc.id = fsc.food_category_id
            WHERE fsc.title = :other
            LIMIT 1
        ", [
            'other' => $other
        ]);
        
        return (isset($results[0])) ? $results[0]['title'] : false;
    }

    public function add_image($fields, $file) {
        $results = $this->DB->insert('food_listing_images', $fields);
        
        if (!isset($results)) return false;

        $full_filename = 'user/' . $fields['filename'] . '.' . $fields['ext'];
        $img_saved = $this->S3->save_object($full_filename, $file);

        return (isset($img_saved)) ? $results : false;
    }
}

?>