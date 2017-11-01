<?php

$settings = [
    'title' => 'Food listing | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB,
    'id' => $_GET['id']
]);

if (isset($FoodListing->id)) {
    $GrowerOperation = new GrowerOperation([
        'DB' => $DB,
        'id' => $FoodListing->grower_operation_id
    ]);

    if (isset($GrowerOperation->id)) {
        $listing_title      = (!empty($FoodListing->other_subcategory)) ? $FoodListing->other_subcategory : $FoodListing->subcategory_title;
        $listing_filename   = (!empty($FoodListing->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/food-listings/' . $FoodListing->filename . '.' . $FoodListing->ext : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';

        $exchange_options_available = [];

        if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) array_push($exchange_options_available, 'delivery');
        if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered) array_push($exchange_options_available, 'pickup');
        if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered) array_push($exchange_options_available, 'meetup');

        if ($GrowerOperation->type == 'none') {
            $team_members = $GrowerOperation->get_team_members();

            $ThisUser   = new User([
                'DB' => $DB,
                'id' => $team_members[0]['id']
            ]);

            $op_filename = (!empty($ThisUser->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ThisUser->filename . '.' . $ThisUser->ext : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';
        
            $latitude   = $ThisUser->latitude;
            $longitude  = $ThisUser->longitude;
        
            $name       = $ThisUser->first_name;
            $city       = $ThisUser->bio;
        
            $city       = $ThisUser->city;
            $state      = $ThisUser->state;
        
            $joined_on  = $ThisUser->registered_on;
        } else {
            $op_filename   = (!empty($GrowerOperation->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/grower-operation-images/' . $GrowerOperation->filename . '.' . $GrowerOperation->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';
            
            $latitude   = $GrowerOperation->latitude;
            $longitude  = $GrowerOperation->longitude;
        
            $name       = $GrowerOperation->name;
            $bio        = $GrowerOperation->bio;
        
            $city       = $GrowerOperation->city;
            $state      = $GrowerOperation->state;
        
            $joined_on  = $GrowerOperation->created_on;
        }
        
        if (isset($User) && !empty($User->latitude) && !empty($User->longitude) && !empty($latitude) && !empty($longitude)) {
            $length = getDistance([
                'lat' => $User->latitude,
                'lng' => $User->longitude
            ],
            [
                'lat' => $latitude,
                'lng' => $longitude
            ]);
        
            if ($length < 0.1) {
                $distance['length'] = round($length * 5280);
                $distance['units'] = 'feet';
            } else {
                $distance['length'] = round($length, 1);
                $distance['units'] = 'miles';
            }
        }

        $grower_stars  = '';
        
        // $floor  = floor($grower['rating']);
        // $ceil   = ceil($grower['rating']);
    
        /* for ($i = 0; $i < $floor; $i++) {
            $stars .= '<i class="fa fa-star"></i>';
        } if ($floor < $grower['rating'] && $grower['rating'] < $ceil) {
            $stars .= '<i class="fa fa-star-half-o"></i>';
        } for ($i = $ceil; $i < 5; $i++) {
            $stars .= '<i class="fa fa-star-o"></i>';
        } */

        for ($i = 0; $i < 5; $i++) {
            $grower_stars .= '<i class="fa fa-star"></i>';
        }

        $settings['title'] = ucfirst($listing_title) . ' from ' . $name . ' | Food From Friends';
    }
}

?>