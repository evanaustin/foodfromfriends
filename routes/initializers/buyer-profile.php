<?php

$settings = [
    'title' => 'User profile | Food From Friends'
];

if (isset($Routing->buyer)) {
    $ThisUser = new User([
        'DB'    => $DB,
        'slug'  => $Routing->buyer
    ]);

    if (isset($ThisUser->id)) {
        $is_owner = $ThisUser->id == $User->id;

        $joined_on = new DateTime($ThisUser->registered_on, new DateTimeZone('UTC'));
        $joined_on->setTimezone(new DateTimeZone('America/New_York'));
    
        $WishList = new WishList([
            'DB' => $DB
        ]);

        $wishlist = $WishList->get_wishes($ThisUser->id);

        // $stars = stars($ThisUser->average_rating);
        
        /* $ratings = $ThisUser->retrieve([
            'where' => [
                'grower_operation_id' => $GrowerOperation->id
            ],
            'table' => 'grower_operation_ratings',
            'recent' => true
        ]); */
    
        $settings['title'] = $ThisUser->name . ' | Food From Friends';
    }
}

?>