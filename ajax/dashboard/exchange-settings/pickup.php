<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v) || $v == 0) ${str_replace('-', '_', $k)} = rtrim($v);
// quit($User->id . ' ' . $is_offered . ' ' . $instructions . ' ' . $when);

if($is_offered == 1){
    if (empty($instructions) || empty($when)){
        quit('Make sure to fill out all the information');
    }
}
$Pickup = new Pickup([
    'DB' => $DB
]);

foreach ([
    'is_offered',
    'instructions',
    'when',
] as $required) {
    if (!isset(${$required})) quit('The ' . strtoupper(str_replace('_', ' ', $required)) . ' field is required');
}


if ($Pickup->exists('user_id', $User->id)){
    $updated = $Pickup->update([
        'user_id' => $User->id,
        'is_offered' => $is_offered,
        'instructions' => $instructions,
        'when_details' => $when
    ],'user_id', $User->id);

    if (!$updated) quit('We could not update your pickup preferences');
} else {
    $added = $Pickup->add([
        'user_id' => $User->id,
        'is_offered' => $is_offered,
        'instructions' => $instructions,
        'when_details' => $when
]);

 if (!$added)  quit('Could not save your pickup preferences ');
    
}

echo json_encode($json);

 ?>