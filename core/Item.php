<?php
 
class Item extends Base {
    
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
        $wholesale_price,
        $wholesale_weight,
        $wholesale_units,
        $wholesale_quantity,
        $is_available,
        $packaging,
        $wholesale_packaging,
        $description,
        $average_rating,
        $archived_on;

    public
        $category_title,
        $subcategory_title,
        $variety_title;

    public
        $filename,
        $ext;

    public
        $Image;

    public
        $title,
        $link;

    protected
        $class_dependencies,
        $DB,
        $S3;
        
    function __construct($parameters) {
        $this->table = 'items';

        $this->class_dependencies = [
            'DB',
            'S3'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->populate_fully($this->id);

            $this->Image = new AccountExtension([
                'DB'    => $this->DB,
                'table' => 'item_images',
                'field' => 'item_id',
                'id'    => $this->id,
                'image' => true
            ]);

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
            
            FROM items fl
            
            LEFT JOIN food_categories fc
                ON fc.id    = fl.food_category_id
                
            LEFT JOIN food_subcategories fsc
                ON fsc.id   = fl.food_subcategory_id
                
            LEFT JOIN item_varieties iv
                ON iv.id    = fl.item_variety_id
                
            LEFT JOIN item_images fli
                ON fli.item_id = fl.id
            
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
            
            FROM items fl
            
            LEFT JOIN food_subcategories fsc
                ON fl.food_subcategory_id = fsc.id
            
            LEFT JOIN food_categories fc
                ON fsc.food_category_id = fc.id
            
            LEFT JOIN item_images fli
                ON fl.id = fli.item_id
            
            WHERE fl.grower_operation_id = :grower_operation_id
                AND fl.archived_on IS NULL

            GROUP BY fl.food_category_id

            ORDER BY fl.position
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        return (isset($results[0])) ? $results : false;
    }

    public function get_raw_items($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                i.id,
                i.food_category_id
            FROM items i
            WHERE i.grower_operation_id = :grower_operation_id
                AND i.archived_on IS NULL
            GROUP BY i.food_category_id
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
            
            FROM items fl
            
            LEFT JOIN food_subcategories fsc
                ON fl.food_subcategory_id = fsc.id
            
            LEFT JOIN food_categories fc
                ON fsc.food_category_id = fc.id
            
            LEFT JOIN item_images fli
                ON fl.id = fli.item_id
            
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