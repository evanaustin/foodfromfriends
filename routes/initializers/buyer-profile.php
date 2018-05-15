<?php

$settings = [
    'title' => 'Buyer profile | Food From Friends'
];

if (isset($Routing->buyer)) {
    $BuyerAccount = new BuyerAccount([
        'DB'    => $DB,
        'slug'  => $Routing->buyer
    ]);

    if (isset($BuyerAccount->id)) {
        $is_owner = $BuyerAccount->id == $User->BuyerAccount->id;

        $joined_on = new DateTime($BuyerAccount->registered_on, new DateTimeZone('UTC'));
        $joined_on->setTimezone(new DateTimeZone('America/New_York'));
    
        $WishList = new WishList([
            'DB' => $DB
        ]);

        $wishlist = $WishList->get_wishes($BuyerAccount->id);

        $wishlist_description = $WishList->retrieve([
            'where' => [
                'buyer_account_id' => $BuyerAccount->id
            ],
            'table' => 'wish_list_descriptions',
            'limit' => 1
        ]);

        // $stars = stars($BuyerAccount->average_rating);
        
        /* $ratings = $BuyerAccount->retrieve([
            'where' => [
                'grower_operation_id' => $GrowerOperation->id
            ],
            'table' => 'grower_operation_ratings',
            'recent' => true
        ]); */
    
        $settings['title'] = "{$BuyerAccount->name} | Food From Friends";
    }
}

?>