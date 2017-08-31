<?php 
$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

foreach ($_POST as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Gump->validation_rules([
    'is-offered'        => 'required|boolean',
	'distance'          => ($is_offered ? 'required|' : '' ) . 'regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
    'delivery-type'     => ($is_offered ? 'required|' : '' ) . 'alpha',
	'free-distance'     => ((!empty($delivery_type) && $delivery_type == 'conditional') ? 'required|' : '' ) . 'regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
	'fee'               => ((!empty($delivery_type) && $delivery_type != 'free') ? 'required|' : '' ) . 'regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 10000',
	'pricing-rate'      => ((!empty($delivery_type) && $delivery_type != 'free') ? 'required|' : '' ) . 'alpha_dash'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'distance'      => 'trim|whole_number',
    'delivery-type' => 'trim|sanitize_string',
	'free-distance' => 'trim|whole_number',
	'fee'           => 'trim|sanitize_floats',
	'pricing-rate'  => 'trim|sanitize_string',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Delivery = new Delivery([
    'DB' => $DB
]);

if ($Delivery->exists('user_id', $User->id)) {
    $updated = $Delivery->update([
        'user_id' => $User->id,
        'is_offered' => $is_offered,
        'distance' => $distance,
        'delivery_type' => $delivery_type,
        'free_distance' => $free_distance,
        'pricing_rate'=> $pricing_rate,
        'fee' => $fee * 100
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

