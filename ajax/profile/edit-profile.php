<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v) || $v == 0) ${str_replace('-', '_', $k)} = rtrim($v);

  
$EditUser = new User([
    'DB' => $DB,
    'id' => $_GET['id']
]);

$dob = strtotime($day . ' ' . $month . ' ' . $year);

if ($User->exists('id',$User->id)) { 
          
        $updated = $User->update([
            'user_id' => $User->id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'dob' => $dob,
            'gender' => $gender, 
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'city' => $city,
            'state' => $state,
            'zipcode' => $zipcode,
            'bio' => $bio
        ], 
        'id', $User->id); 

    } else {
        $added = $User->add([
            'user_id' => $User->id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'dob' => $dob,
            'gender' => $gender, 
            'address_line_1' => $address_line_1,
            'address_line_2' => $address_line_2,
            'city' => $city,
            'state' => $state,
            'zipcode' => $zipcode,
            'bio' => $bio
            
        ]);
    }

echo json_encode($json);

    ?>