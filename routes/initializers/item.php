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
    
            $active_ex_op = (isset($User, $User->BuyerAccount->ActiveOrder->Growers[$GrowerOperation->id]->Exchange)) ? $User->BuyerAccount->ActiveOrder->Growers[$GrowerOperation->id]->Exchange->type : null;
    
            if (isset($User, $User->BuyerAccount, $User->BuyerAccount->Address, $User->BuyerAccount->Address->latitude, $User->BuyerAccount->Address->longitude)) {
                $geocode    = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins={$User->BuyerAccount->Address->latitude},{$User->BuyerAccount->Address->longitude}&destinations={$GrowerOperation->latitude},{$GrowerOperation->longitude}&key=" . GOOGLE_MAPS_KEY);
                $output     = json_decode($geocode);
                $distance   = explode(' ', $output->rows[0]->elements[0]->distance->text);
                $distance_miles = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
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