<?php
 
class Item extends Base {
    
    public
        $id,
        $grower_operation_id,
        $item_subcategory_id,
        $item_variety_id,
        $name,
        $price,
        $quantity,
        $package_type_id,
        $measurement,
        $metric_id,
        $description,
        $is_wholesale,
        $average_rating,
        $archived_on;
    
    public
        $title,
        $package_metric_title,
        $link;

    public
        $category_id,
        $category_title,
        $subcategory_title,
        $variety_title,
        $package_type,
        $metric;

    public
        $Image;

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

            $this->title = (!empty($this->name)) ? $this->name : ucfirst((!empty($this->variety_title) ? $this->variety_title . ' ' : '') . $this->subcategory_title);
            $this->package_metric_title = ucfirst(((!empty($this->measurement) && !empty($this->metric)) ? "{$this->measurement} {$this->metric} {$this->package_type}" : $this->package_type));

            $Slug = new Slug([
                'DB' => $this->DB
            ]);

            $this->link = (($this->is_wholesale) ? 'wholesale' : 'retail') . '/' . $Slug->slugify($this->category_title) . '/' . $Slug->slugify($this->subcategory_title) . (isset($this->variety_title) ? '/' . $Slug->slugify($this->variety_title) : '');
        }
    }

    private function populate_fully($id) {
        $results = $this->DB->run('
            SELECT 
                i.*,
                ic.id       AS item_category_id,
                ic.title    AS category_title,
                isc.title   AS subcategory_title,
                iv.title    AS variety_title,
                pt.title    AS package_type,
                im.title    AS metric
            
            FROM items i
            
            JOIN item_subcategories isc
                ON isc.id   = i.item_subcategory_id
            
            JOIN item_categories ic
                ON ic.id    = isc.item_category_id
                
            LEFT JOIN item_varieties iv
                ON iv.id    = i.item_variety_id
            
            LEFT JOIN item_package_types pt
                ON pt.id    = i.package_type_id
            
            LEFT JOIN item_metrics im
                ON im.id    = i.metric_id
                
            WHERE i.id = :id
        
            LIMIT 1
        ', [
            'id' => $id
        ]);

        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
    }

    /*
     *  $options = [
     *      is_available  : boolean
     *      is_wholesale  : boolean
     *      is_archived   : boolean
     *  ];
    */
    public function get_items($grower_operation_id, $options = null) {
        $bind = [
            'grower_operation_id' => $grower_operation_id
        ];

        if (isset($options)) {
            if (isset($options['is_wholesale'])) {
                $bind['is_wholesale'] = $options['is_wholesale'];
            }
        }

        $sql = "
            SELECT 
                i.id,
                iv.id   AS item_variety_id,
                isc.id  AS item_subcategory_id,
                ic.id   AS item_category_id
            
            FROM items i
            
            LEFT JOIN item_varieties iv
                ON iv.id = i.item_variety_id

            JOIN item_subcategories isc
                ON isc.id = i.item_subcategory_id
            
            JOIN item_categories ic
                ON ic.id = isc.item_category_id
            
            WHERE i.grower_operation_id = :grower_operation_id
        ";

        if (isset($options['is_available']) && $options['is_available']) {
            $sql .= "
                AND i.quantity > 0
            ";
        }

        if (isset($options['is_wholesale'])) {
            $sql .= "
                AND i.is_wholesale = :is_wholesale
            ";
        }

        if (!isset($options['is_archived'])) {
            $sql .= "
                AND i.archived_on IS NULL
            ";
        }

        $sql .= "ORDER BY ic.id asc, isc.title asc, iv.title asc, i.quantity desc";

        $results = $this->DB->run($sql, $bind);

        return (isset($results[0])) ? $results : false;
    }

    public function get_available_items($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                i.*,
                isc.title AS subcategory_title,
                isc.item_category_id,
                i.title AS category_title
            
            FROM items i
            
            LEFT JOIN item_subcategories isc
                ON i.item_subcategory_id = isc.id
            
            LEFT JOIN item_categories ic
                ON isc.item_category_id = ic.id
            
            WHERE i.grower_operation_id = :grower_operation_id
        ', [
            'grower_operation_id' => $grower_operation_id,
        ]);

        return (isset($results[0])) ? $results : false;
    }

    public function get_category_associations() {
        $results = $this->DB->run('
            SELECT 
                ic.id       AS category_id,
                ic.title    AS category_title,
                isc.id      AS subcategory_id,
                isc.title   AS subcategory_title,
                iv.id       AS variety_id,
                iv.title    AS variety_title
            
            FROM item_categories ic
            
            LEFT JOIN item_subcategories isc
                ON isc.item_category_id = ic.id

            LEFT JOIN item_varieties iv
                ON iv.item_subcategory_id = isc.id
        ');

        return $results;
    }

}

?>