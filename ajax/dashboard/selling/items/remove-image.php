<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'item_id'   => 'required|integer',
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'item_id'   => 'trim|whole_number',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Item = new Item([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $item_id
]);

if (!empty($Item->Image->filename)) {
    $similar_items = $Item->retrieve([
        'where' => [
            'image_id' => $Item->Image->image_id
        ],
        'table' => 'item_images'
    ]);
    
    // only delete the image file if only this item references it
    if (count($similar_items) == 1) {
        $S3->delete_objects([
            ENV . "/item-images/{$Item->Image->filename}.{$Item->Image->ext}"
        ]);
    
        $image_record_removed = $Item->delete('filename', $Item->Image->filename, 'images');
    }

    $record_removed = $Item->delete('item_id', $Item->id, 'item_images');

    if (!$record_removed) quit('Could not remove item image');
} else {
    quit('There was no image to remove');
}

echo json_encode($json);

?>  
