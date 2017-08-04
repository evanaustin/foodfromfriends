<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'price'             => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
	'weight'            => 'required|regex,/^[0-9]+$/|min_numeric, 1|max_numeric, 10000',
	'units'             => 'required|alpha',
	'quantity'          => 'required|regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
	'is-available'      => 'required|boolean'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'price'             => 'trim|sanitize_floats',
	'weight'            => 'trim|whole_number',
	'units'             => 'trim|sanitize_string',
	'stock'             => 'trim|whole_number',
	'description'       => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$FoodListing = new FoodListing([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $id
]);

$listing_edited = $FoodListing->update([
    'price'         => $price * 100,
    'weight'        => $weight,
    'units'         => $units,
    'quantity'      => $quantity,
    'is_available'  => $is_available,
    'description'   => $description
], 'id', $id);

if (!$listing_edited) {
    quit('Could not edit listing');
}

$file = file_get_contents($_FILES['listing-image']['tmp_name']);
$filename = 'fl.' . $id . '.fc.' . $FoodListing->food_category_id . '.fsc.' . (empty($FoodListing->other_subcategory) ? $FoodListing->food_subcategory_id : $$FoodListing->other_subcategory) . '.u.' . $User->id;
$ext = (explode('/', $_FILES['listing-image']['type'])[1] == 'jpeg') ? 'jpg' : 'png';

// check file size

// check file type

// check other stuff

// crop file dimensions

if (!empty($FoodListing->filename)) {
    $img_removed = $S3->delete_objects([
        'user/' . $FoodListing->filename . $FoodListing->ext
    ], $file);

    if (!$img_removed) quit('Could not remove old image');
    
    $record_edited = $FoodListing->update([
        'ext' => $ext
    ], 'id', $id, 'food_listing_images');

    if (!$record_edited) quit('Could not edit image record');
} else {
    $record_added = $FoodListing->add([
        'food_listing_id' => $id,
        'filename' => $filename,
        'ext' => $ext
    ], 'food_listing_images');

    if (!$record_added) {
        quit('Could not add image record');
    }
}

$img_added = $S3->save_object('user/' . $filename . '.' . $ext, $file);

if (!$img_added) quit('Could not add new image');

echo json_encode($json);

?>  
