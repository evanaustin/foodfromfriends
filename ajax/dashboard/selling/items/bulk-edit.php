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
        'retail-price'      => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
        'wholesale-price'   => 'regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
        'quantity'          => 'required|regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
    ]);
    
    $validated_data = $Gump->run($item);
    
    if ($validated_data === false) {
        quit($Gump->get_readable_errors()[0]);
    }
    
    $Gump->filter_rules([
        'retail-price'      => 'trim|sanitize_floats',
        'wholesale-price'   => 'trim|sanitize_floats',
        'quantity'          => 'trim|whole_number'
    ]);
    
    $prepared_data = $Gump->run($validated_data);
    
    unset($available);

    foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;
    
    $Item = new Item([
        'DB' => $DB,
        'id' => $id
    ]);
    
    $updated = $Item->update([
        'quantity'          => $quantity,
        'is_available'      => (isset($available) && $available == 'on') ? 1 : 0,
        'price'             => $retail_price * 100,
        'wholesale_price'   => $wholesale_price * 100,
    ]);
    
    if (!$updated) {
        quit("Could not edit {$Item->name}");
    }
}

echo json_encode($json);

?>  