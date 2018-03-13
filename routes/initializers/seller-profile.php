<?php

$settings = [
    'title' => 'Seller profile | Food From Friends'
];

if (isset($Routing->seller)) {
    $GrowerOperation = new GrowerOperation([
        'DB'    => $DB,
        'slug'  => $Routing->seller
    ], [
        'details'   => true,
        'exchange'  => true,
        'team'      => true
    ]);
    
    if (isset($GrowerOperation)) {
        $is_owner = isset($User) && ((isset($GrowerOperation->Owner) && $GrowerOperation->Owner->id == $User->id) || isset($GrowerOperation->TeamMembers[$User->id]));
    
        if ($GrowerOperation->is_active || $is_owner) {
            $joined_on = new DateTime($GrowerOperation->details['joined'], new DateTimeZone('UTC'));
            $joined_on->setTimezone(new DateTimeZone('America/New_York'));
        
            if (isset($User) 
            && !empty($User->latitude) && !empty($User->longitude) 
            && !empty($GrowerOperation->details['lat']) && !empty($GrowerOperation->details['lng'])) {
                $length = getDistance([
                    'lat' => $User->latitude,
                    'lng' => $User->longitude
                ],
                [
                    'lat' => $GrowerOperation->details['lat'],
                    'lng' => $GrowerOperation->details['lng']
                ]);
            
                if ($length < 0.1) {
                    $distance['length'] = round($length * 5280);
                    $distance['units'] = 'feet';
                } else {
                    $distance['length'] = round($length, 1);
                    $distance['units'] = 'miles';
                }
            }
            
            $FoodListing = new FoodListing([
                'DB' => $DB
            ]);
        
            $grower_stars = stars($GrowerOperation->average_rating);
            
            $listings = $FoodListing->get_all_listings($GrowerOperation->id);
        
            $ratings = $GrowerOperation->retrieve([
                'where' => [
                    'grower_operation_id' => $GrowerOperation->id
                ],
                'table' => 'grower_operation_ratings',
                'recent' => true
            ]);
        
            $settings['title'] = "{$GrowerOperation->name} | Food From Friends";
        }
    }
}

?>