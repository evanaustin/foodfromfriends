<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v) || $v == 0) ${str_replace('-', '_', $k)} = rtrim($v);
// quit($User->id . ' ' . $is_offered . ' ' . $instructions . ' ' . $when);
// quit($details_id);
// if($is_offered == 1){
  
$Meetup = new Meetup([
    'DB' => $DB
]);
// user_id variable


foreach ([
    'is_offered',
] as $required) {
    if (!isset(${$required})) quit('The ' . strtoupper(str_replace('_', ' ', $required)) . ' field is required');
}


// $test = $Meetup->exists_details($User->id,3);
// quit($test);

if ($Meetup->exists('user_id',$User->id)) { 
    $updated = $Meetup->update([
        'user_id' => $User->id,
        'is_offered' => $is_offered
    ],'user_id', $User->id);

    if (!$updated) quit('We could not update your meetup preferences');
    
    if ($Meetup->exists_details($User->id,1) && $details_id == '1') {  
                  
        $updated_details_1 = $Meetup->update_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'when_details' => $when_details,
            'details_id'=> $details_id,
            'is_available' => $is_available
        ], [
            'user_id' => $User->id,
            'details_id' => 1
        ]);
    } elseif($details_id == '1') {
        $added_details_1 = $Meetup->add_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'when_details' => $when_details,
            'details_id'=> $details_id,
            'is_available' => $is_available
        ]);
    }
   if ($Meetup->exists_details($User->id,2) && $details_id_2 == '2') {    
        $updated_details_2 = $Meetup->update_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1_2,
            'address_line_2' => $address_line_2_2,
            'city' => $city_2,
            'state' => $state_2,
            'zip' => $zip_2,
            'when_details' => $when_details_2,
            'details_id'=> $details_id_2,
            'is_available' => $is_available_2
        ], [
            'user_id' => $User->id,
            'details_id' => 2
        ]);
    } elseif($details_id_2 == '2') {
        $added_details_2 = $Meetup->add_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1_2,
            'address_line_2' => $address_line_2_2,
            'city' => $city_2,
            'state' => $state_2,
            'zip' => $zip_2,
            'when_details' => $when_details_2,
            'details_id'=> $details_id_2,
            'is_available' => $is_available_2
        ]);
    }
  if ($Meetup->exists_details($User->id,3) && $details_id_3 == '3') {    
        $updated_details_3 = $Meetup->update_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1_3,
            'address_line_2' => $address_line_2_3,
            'city' => $city_3,
            'state' => $state_3,
            'zip' => $zip_3,
            'when_details' => $when_details_3,
            'details_id'=> $details_id_3,
            'is_available' => $is_available_3
    ], [
            'user_id' => $User->id,
            'details_id' => $details_id_3
    ]);
    } else if($details_id_3 == '3') {
        $added_details_3 = $Meetup->add_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1_3,
            'address_line_2' => $address_line_2_3,
            'city' => $city_3,
            'state' => $state_3,
            'zip' => $zip_3,
            'when_details' => $when_details_3,
            'details_id'=> $details_id_3,
            'is_available' => $is_available_3
    ]);
    } 
} else {
    $added = $Meetup->add([
        'user_id' => $User->id,
        'is_offered' => $is_offered
]);

    if (!$added)  quit('Could not save your meetup preferences ');
   
    if($details_id == '1'){
            $added_details = $Meetup->add_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'when_details' => $when_details,
            'details_id'=> $details_id,
            'is_available' => $is_available
        ]);

        }
    if($details_id_2 == '2') {
        $added_details_2 = $Meetup->add_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1_2,
            'address_line_2' => $address_line_2_2,
            'city' => $city_2,
            'state' => $state_2,
            'zip' => $zip_2,
            'when_details' => $when_details_2,
            'details_id'=> $details_id_2,
            'is_available' => $is_available_2
        ]);
    }

    if($details_id_3 == '3') {
        $added_details_3 = $Meetup->add_details([
            'user_id' => $User->id,
            'address_line_1' => $address_line_1_3,
            'address_line_2' => $address_line_2_3,
            'city' => $city_3,
            'state' => $state_3,
            'zip' => $zip_3,
            'when_details' => $when_details_3,
            'details_id'=> $details_id_3,
            'is_available' => $is_available_3
        ]); 
        }

    }

echo json_encode($json);
 ?>