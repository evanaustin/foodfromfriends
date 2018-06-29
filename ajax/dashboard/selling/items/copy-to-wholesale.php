<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

foreach($_POST['items'] as $id => $item) {
    if (!isset($item['select']) || $item['select'] != 'on') continue;
    
    $Item = new Item([
        'DB' => $DB,
        'id' => $id
    ]);
    
    $added = $Item->add([
        'grower_operation_id'   => $User->GrowerOperation->id,
        'item_subcategory_id'   => $Item->item_subcategory_id,
        'item_variety_id'       => $Item->item_variety_id,
        'name'                  => (!empty($Item->name) ? $Item->name : NULL),
        'is_wholesale'          => 1,
        'price'                 => $Item->price,
        'quantity'              => 0,
        'package_type_id'       => $Item->package_type_id,
        'measurement'           => (!empty($Item->measurement)) ? $Item->measurement : 0,
        'metric_id'             => (!empty($Item->metric_id)) ? $Item->metric_id : 0
    ]);
    
    if (!$added) {
        quit("Could not convert {$Item->name} to wholesale");
    }

    if (!empty($Item->Image->id)) {
        $Item->add([
            'item_id'   => $added['last_insert_id'],
            'image_id'  => $Item->Image->id
        ], 'item_images');
    }
}

echo json_encode($json);

?>  