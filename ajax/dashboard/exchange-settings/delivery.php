<?php 
$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v) || $v == 0) ${str_replace('-', '_', $k)} = rtrim($v);

$Delivery = new Delivery([
    'DB' => $DB
]);

foreach ([
    'is_offered'
] as $required) {
    if (!isset(${$required})) quit('The ' . strtoupper(str_replace('_', ' ', $required)) . ' field is required');
}

if ($Delivery->exists('user_id', $User->id)){
    $updated = $Delivery->update([
        'user_id' => $User->id,
        'is_offered' => $is_offered,
        'distance' => (isset($distance) ? $distance : ''),
        'free_delivery' => (isset($free_delivery) ? $free_delivery : ''),
        'free_miles' => (isset($free_miles) ? $free_miles : ''),
        'pricing_rate'=> (isset($pricing_rate) ? $pricing_rate : ''),
        'fee'=> (isset($fee) ? $fee : '')
    ],'user_id', $User->id);

    if (!$updated) quit('We could not update your delivery preferences');
} else {
    $added = $Delivery->add([
        'user_id' => $User->id,
        'is_offered' => $is_offered,
        'distance' => (isset($distance) ? $distance : ''),
        'free_delivery' => (isset($free_delivery) ? $free_delivery : ''),
        'free_miles' => (isset($free_miles) ? $free_miles : ''),
        'pricing_rate'=> (isset($pricing_rate) ? $pricing_rate : ''),
        'fee'=> (isset($fee) ? $fee : '')
    ]);

    if (!$added) quit('We could not add your delivery preferences');
}

echo json_encode($json);

?>  


