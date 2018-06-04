<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

foreach($_POST['items'] as $id => $item) {
    $item = $Gump->sanitize($item);
    
    $Gump->validation_rules([
        'position'      => 'integer',
        'variety-id'    => 'integer',
        'name'          => 'regex,/^[a-zA-z\s:]+$/',
        'measurement'   => 'regex,/^([0-9]*[.x\s])*[0-9]+$/|max_len, 10',
        'metric'        => 'integer',
        'package-type'  => 'required|integer',
        'price'         => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
        'quantity'      => 'regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
        'is_available'  => 'alpha',
        'is_wholesale'  => 'alpha'
    ]);
    
    $validated_data = $Gump->run($item);
    
    if ($validated_data === false) {
        quit($Gump->get_readable_errors()[0]);
    }
    
    $Gump->filter_rules([
        'position'      => 'trim|whole_number',
        'variety-id'    => 'trim|whole_number',
        'name'          => 'trim|sanitize_string',
        'package-type'  => 'trim|whole_number',
        'measurement'   => 'trim|sanitize_string',
        'metric'        => 'trim|whole_number',
        'price'         => 'trim|sanitize_floats',
        'quantity'      => 'trim|whole_number',
        'is_available'  => 'trim|sanitize_string',
        'is_wholesale'  => 'trim|sanitize_string'
    ]);
    
    $prepared_data = $Gump->run($validated_data);
    
    // reset non-required fields
    unset($position, $variety_id, $name, $measurement, $metric, $quantity, $is_available, $is_wholesale);

    foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;
    
    $Item = new Item([
        'DB' => $DB,
        'id' => $id
    ]);
    
    $updated = $Item->update([
        'position'          => (!empty($position) ? $position : 0),
        'item_variety_id'   => (!empty($variety_id) ? $variety_id : 0),
        'name'              => (!empty($name) ? $name : NULL),
        'is_available'      => (isset($is_available) && $is_available == 'on') ? 1 : 0,
        'is_wholesale'      => (isset($is_wholesale) && $is_wholesale == 'on') ? 1 : 0,
        'price'             => $price * 100,
        'quantity'          => (isset($quantity)) ? $quantity : 0,
        'package_type_id'   => $package_type,
        'measurement'       => (!empty($measurement) && !empty($metric)) ? $measurement : 0,
        'metric_id'         => (!empty($measurement) && !empty($metric)) ? $metric : 0,
    ]);
    
    if (!$updated) {
        quit("Could not edit {$Item->name}");
    }
}

echo json_encode($json);

?>  