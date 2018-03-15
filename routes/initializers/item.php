<?php

$settings = [
    'title' => 'Item listing | Food From Friends'
];

if (isset($Routing->item_type)) {
    $GrowerOperation = new GrowerOperation([
        'DB' => $DB,
        'slug' => $Routing->seller
    ], [
        'team'      => true,
        'exchange'  => true
    ]);

    if (isset($GrowerOperation)) {
        $is_owner = isset($User) && isset($GrowerOperation->TeamMembers[$User->id]);
        
        // Find seller's listing that matches this subcategory
        $results = $GrowerOperation->retrieve([
            'where' => [
                'grower_operation_id' => $GrowerOperation->id,
                (($Routing->item_type == 'subcategory') ? 'food_subcategory_id' : 'item_variety_id') => $Routing->item_id
            ],
            'table' => 'food_listings',
            'limit' => 1
        ]);
    
        $FoodListing = new FoodListing([
            'DB' => $DB,
            'id' => $results['id']
        ]);
        
        if (isset($FoodListing->id)) {
            $exchange_options_available = [];
    
            if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered)   $exchange_options_available []= 'delivery';
            if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered)       $exchange_options_available []= 'pickup';
            if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered)       $exchange_options_available []= 'meetup';
    
            $active_ex_op = (isset($User, $User->ActiveOrder->Growers[$GrowerOperation->id]->Exchange)) ? $User->ActiveOrder->Growers[$GrowerOperation->id]->Exchange->type : null;
    
            if (isset($User) 
            && !empty($User->latitude) && !empty($User->longitude) 
            && !empty($GrowerOperation->latitude) && !empty($GrowerOperation->longitude)) {
                
                $length = getDistance([
                    'lat' => $User->latitude,
                    'lng' => $User->longitude
                ], [
                    'lat' => $GrowerOperation->latitude,
                    'lng' => $GrowerOperation->longitude
                ]);
            
                if ($length < 0.1) {
                    $distance['length'] = round($length * 5280);
                    $distance['units']  = 'feet';
                } else {
                    $distance['length'] = round($length, 1);
                    $distance['units']  = 'miles';
                }
            }
    
            $grower_stars   = ($GrowerOperation->average_rating == 0) ? 'New' : stars($GrowerOperation->average_rating);
            $item_stars     = ($FoodListing->average_rating == 0) ? 'New' : stars($FoodListing->average_rating);
    
            $ratings = $FoodListing->retrieve([
                'where' => [
                    'food_listing_id' => $FoodListing->id
                ],
                'table' => 'food_listing_ratings',
                'recent' => true
            ]);
    
            $settings['title'] = "{$FoodListing->title} from {$GrowerOperation->name} | Food From Friends";
        }
    }
}


?>