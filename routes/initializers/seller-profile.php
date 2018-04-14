<?php

$settings = [
    'title' => 'Seller profile | Food From Friends'
];

if (isset($Routing->seller)) {
    $Seller = new GrowerOperation([
        'DB'    => $DB,
        'slug'  => $Routing->seller
    ], [
        'details'   => true,
        'exchange'  => true,
        'team'      => true
    ]);
    
    if (isset($Seller)) {
        $is_owner = isset($User) && ((isset($Seller->Owner) && $Seller->Owner->id == $User->id) || isset($Seller->TeamMembers[$User->id]));
    
        if ($Seller->is_active || $is_owner) {
            $joined_on = new DateTime($Seller->created_on, new DateTimeZone('UTC'));
            $joined_on->setTimezone(new DateTimeZone('America/New_York'));
        
            if (isset($User) 
            && !empty($User->latitude) && !empty($User->longitude) 
            && !empty($Seller->latitude) && !empty($Seller->longitude)) {
                $length = getDistance([
                    'lat' => $User->latitude,
                    'lng' => $User->longitude
                ],
                [
                    'lat' => $Seller->latitude,
                    'lng' => $Seller->longitude
                ]);
            
                if ($length < 0.1) {
                    $distance['length'] = round($length * 5280);
                    $distance['units'] = 'feet';
                } else {
                    $distance['length'] = round($length, 1);
                    $distance['units'] = 'miles';
                }
            }

            if (isset($User, $User->delivery_latitude, $User->delivery_longitude)) {
                $geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $User->delivery_latitude . ',' . $User->delivery_longitude . '&destinations=' . $Seller->latitude . ',' . $Seller->longitude . '&key=' . GOOGLE_MAPS_KEY);
                $output = json_decode($geocode);
                $distance = explode(' ', $output->rows[0]->elements[0]->distance->text);
                
                $delivery_distance = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
            }
            
            $Item = new FoodListing([
                'DB' => $DB
            ]);
        
            $grower_stars = stars($Seller->average_rating);
            
            $listings = $Item->get_all_listings($Seller->id);

            if (isset($User, $User->ActiveOrder, $User->ActiveOrder->Growers[$Seller->id])) {
                $SubOrder = $User->ActiveOrder->Growers[$Seller->id];
            }
        
            $ratings = $Seller->retrieve([
                'where' => [
                    'grower_operation_id' => $Seller->id
                ],
                'table' => 'grower_operation_ratings',
                'recent' => true
            ]);
        
            $settings['title'] = "{$Seller->name} | Food From Friends";
        }
    }
}

?>