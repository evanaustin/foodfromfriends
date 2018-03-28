<?php
 
class FoodListing extends Base {
    
    public
        $id,
        $name,
        $grower_operation_id,
        $food_category_id,
        $food_subcategory_id,
        $item_variety_id,
        $price,
        $weight,
        $units,
        $quantity,
        $is_available,
        $description,
        $average_rating,
        $archived_on,
        $category_title,
        $subcategory_title,
        $variety_title,
        $filename,
        $ext;

    public
        $title,
        $link;

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
            $this->populate_fully($this->id);

            $this->title = (isset($this->name)) ? $this->name : ucfirst((!empty($this->variety_title) ? $this->variety_title . ' ' : '') . $this->subcategory_title);

            $Slug = new Slug([
                'DB' => $this->DB
            ]);

            $this->link = $Slug->slugify($this->category_title) . '/' . $Slug->slugify($this->subcategory_title) . (isset($this->variety_title) ? '/' . $Slug->slugify($this->variety_title) : '');
        }
    }

    private function populate_fully($id) {
        $results = $this->DB->run('
            SELECT 
                fl.*,
                fc.title    AS category_title,
                fsc.title   AS subcategory_title,
                iv.title    AS variety_title,
                fli.filename,
                fli.ext
            
            FROM food_listings fl
            
            LEFT JOIN food_categories fc
                ON fc.id    = fl.food_category_id
                
            LEFT JOIN food_subcategories fsc
                ON fsc.id   = fl.food_subcategory_id
                
            LEFT JOIN item_varieties iv
                ON iv.id    = fl.item_variety_id
                
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

    public function get_all_listings($grower_operation_id) {
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
            
            WHERE fl.grower_operation_id = :grower_operation_id
                AND fl.archived_on IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        return (isset($results[0])) ? $results : false;
    }

    public function get_available_listings($grower_operation_id) {
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
            
            WHERE fl.grower_operation_id = :grower_operation_id
                AND fl.is_available = :is_available
        ', [
            'grower_operation_id' => $grower_operation_id,
            'is_available' => 1
        ]);

        return (isset($results[0])) ? $results : false;
    }

    public function get_category_associations() {
        $results = $this->DB->run('
            SELECT 
                fc.id       AS category_id,
                fc.title    AS category_title,
                fsc.id      AS subcategory_id,
                fsc.title   AS subcategory_title,
                iv.id       AS variety_id,
                iv.title    AS variety_title
            
            FROM food_categories fc
            
            LEFT JOIN food_subcategories fsc
                ON fsc.food_category_id = fc.id

            LEFT JOIN item_varieties iv
                ON iv.food_subcategory_id = fsc.id
        ');

        return $results;
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