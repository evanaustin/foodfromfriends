<?php

$settings = [
    'title' => 'Wish list | Food From Friends'
];

$Item = new FoodListing([
    'DB' => $DB
]);
        
// Get item category:subcateory associations & construct data structure to map relationships
$raw_assns      = $Item->get_category_associations();
$category_assns = [];

foreach ($raw_assns as $assn) {
    if (!isset($category_assns[$assn['category_id']])) {
        $category_assns[$assn['category_id']] = [
            'title' => $assn['category_title'],
            'subcategories' => []
        ];
    }

    if (!isset($category_assns[$assn['category_id']]['subcategories'][$assn['subcategory_id']])) {
        $category_assns[$assn['category_id']]['subcategories'][$assn['subcategory_id']] = [
            'title' => $assn['subcategory_title'],
            'varieties' => []
        ];
    }
    
    if (isset($assn['variety_id']) && !isset($category_assns[$assn['category_id']]['subcategories'][$assn['subcategory_id']['varieties'][$assn['variety_id']]])) {
        $category_assns[$assn['category_id']]['subcategories'][$assn['subcategory_id']]['varieties'][$assn['variety_id']] = [
            'title' => $assn['variety_title'],
        ];
    }
}

$raw_wishlist = $User->retrieve([
    'where' => [
        'buyer_account_id' => $User->BuyerAccount->id
    ],
    'table' => 'wish_list_items'
]);

$extant_subcategories = [];
$extant_varieties = [];

foreach($raw_wishlist as $wish) {
    if (!empty($wish['item_variety_id'])) {
        $extant_varieties[$wish['item_variety_id']] = $wish['id'];
    } else {
        $extant_subcategories[$wish['item_subcategory_id']] = $wish['id'];
    }
}

?>