<?php
 
class WishList extends Base {
    
    public
        $id,
        $buyer_account_id,
        $item_category_id,
        $item_subcategory_id,
        $item_variety_id;

    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'wish_list_items';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);

        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    public function get_wishes($buyer_account_id) {
        $raw = $this->retrieve_wishes($buyer_account_id);
        return $this->hash_wishes($raw);   
    }

    public function retrieve_wishes($buyer_account_id) {
        $results = $this->DB->run('
            SELECT 
                wl.item_category_id     AS category_id,
                wl.item_subcategory_id  AS subcategory_id,
                ic.title                AS category_title,
                isc.title               AS subcategory_title
            
            FROM wish_list_items wl
            
            JOIN item_categories ic
                ON ic.id    = wl.item_category_id
                
            JOIN item_subcategories isc
                ON isc.id   = wl.item_subcategory_id
            
            WHERE wl.buyer_account_id = :buyer_account_id
        ', [
            'buyer_account_id' => $buyer_account_id
        ]);

        return (isset($results[0])) ? $results : false;
    }

    public function hash_wishes($raw) {
        $wishlist = [];

        /* foreach ($raw as $record) {
            if (!isset($wishlist[$record['category_title']])) {
                $wishlist[$record['category_title']] = [];
            }
        
            if (!isset($wishlist[$record['category_title']][$record['subcategory_title']])) {
                $wishlist[$record['category_title']][$record['subcategory_title']] = [];
            }
        } */

        foreach ($raw as $assn) {
            if (!isset($wishlist[$assn['category_id']])) {
                $wishlist[$assn['category_id']] = [
                    'title' => $assn['category_title'],
                    'subcategories' => []
                ];
            }
        
            if (!isset($wishlist[$assn['category_id']]['subcategories'][$assn['subcategory_id']])) {
                $wishlist[$assn['category_id']]['subcategories'][$assn['subcategory_id']] = [
                    'title' => $assn['subcategory_title'],
                    'varieties' => []
                ];
            }
            
            if (isset($assn['variety_id']) && !isset($wishlist[$assn['category_id']]['subcategories'][$assn['subcategory_id']['varieties'][$assn['variety_id']]])) {
                $wishlist[$assn['category_id']]['subcategories'][$assn['subcategory_id']]['varieties'][$assn['variety_id']] = [
                    'title' => $assn['variety_title'],
                ];
            }
        }

        return $wishlist;
    }
}

?>