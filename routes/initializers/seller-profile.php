<?php

$settings = [
    'title' => 'Seller profile | Food From Friends'
];

if (isset($Routing->seller)) {
    // initialize Seller
    $Seller = new GrowerOperation([
        'DB'    => $DB,
        'slug'  => $Routing->seller
    ], [
        'exchange'  => true,
        'team'      => true
    ]);
    
    if (isset($Seller)) {
        // check if User is owner of Seller
        $is_owner = isset($User) && ((isset($Seller->Owner) && $Seller->Owner->id == $User->id) || isset($Seller->TeamMembers[$User->id]));
    
        // check if Seller is active
        if ($Seller->is_active || $is_owner) {
            // configure date joined on
            $joined_on = new DateTime($Seller->created_on, new DateTimeZone('UTC'));
            $joined_on->setTimezone(new DateTimeZone('America/New_York'));
        
            // calculate delivery distance
            if (isset($User, $User->BuyerAccount, $User->BuyerAccount->Address, $User->BuyerAccount->Address->latitude, $User->BuyerAccount->Address->longitude)) {
                $geocode    = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins={$User->BuyerAccount->Address->latitude},{$User->BuyerAccount->Address->longitude}&destinations={$Seller->latitude},{$Seller->longitude}&key=" . GOOGLE_MAPS_KEY);
                $output     = json_decode($geocode);
                $distance   = explode(' ', $output->rows[0]->elements[0]->distance->text);
                $miles_away = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
            }

            // check if wholesale relationship exists between User:BuyerAccount and Seller
            if (isset($User->BuyerAccount)) {
                $wholesale_relationship = $User->BuyerAccount->retrieve([
                    'where' => [
                        'buyer_account_id' => $User->BuyerAccount->id,
                        'seller_id' => $Seller->id,
                        'status'    => 2
                    ],
                    'table' => 'wholesale_relationships'
                ]);
            } else {
                $wholesale_relationship = false;
            }
            
            $Item = new FoodListing([
                'DB' => $DB
            ]);
        
            // retrieve Seller overall rating
            $grower_stars = stars($Seller->average_rating);
            
            // retrieve listings
            $listings = $Item->get_all_listings($Seller->id);

            // initialize OrderGrower if it exists
            if (isset($User, $User->BuyerAccount, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$Seller->id])) {
                $OrderGrower = $User->BuyerAccount->ActiveOrder->Growers[$Seller->id];
            }
        
            // retrieve ratings/reviews
            $ratings = $Seller->retrieve([
                'where' => [
                    'grower_operation_id' => $Seller->id
                ],
                'table' => 'grower_operation_ratings',
                'recent' => true
            ]);
        
            // set page title
            $settings['title'] = "{$Seller->name} | Food From Friends";
        }
    }
}

?>