<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'item-category'     => 'required|integer',
	'item-subcategory'  => 'required|integer',
    'item-variety'      => 'integer',
    'item-name'         => 'alpha_space',
	'quantity'          => 'required|regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
	'is-available'      => 'required|boolean',
	'price'             => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
	'weight'            => 'regex,/^[0-9]+$/|max_numeric, 10000',
	'units'             => 'alpha_space',
	'wholesale-price'   => 'regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
	'wholesale-weight'  => 'regex,/^[0-9]+$/|max_numeric, 10000',
	'wholesale-units'   => 'alpha_space'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'item-category'     => 'trim|whole_number',
	'item-subcategory'  => 'trim|whole_number',
    'item-variety'      => 'trim|whole_number',
	'item-name'         => 'trim|sanitize_string',
	'quantity'          => 'trim|whole_number',
	'price'             => 'trim|sanitize_floats',
	'weight'            => 'trim|whole_number',
	'units'             => 'trim|sanitize_string',
	'wholesale-price'   => 'trim|sanitize_floats',
	'wholesale-weight'  => 'trim|whole_number',
	'wholesale-units'   => 'trim|sanitize_string',
	'packaging'         => 'trim|sanitize_string',
	'description'       => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// manual check that if weight is set then units are too
if (!empty($weight) && empty($units)) {
    quit('Select measurement units for your item retail weight');
}

if (!empty($wholesale_weight) && empty($wholesale_units)) {
    quit('Select measurement units for your item wholesale weight');
}

$Item = new FoodListing([
    'DB' => $DB,
    'S3' => $S3
]);

// ! TODO: make sure category + subcategory + variety are valid
$item_exists = $Item->retrieve([
    'where' => [
        'grower_operation_id'   => $User->GrowerOperation->id,
        'food_category_id'      => $item_category,
        'food_subcategory_id'   => $item_subcategory,
        'item_variety_id'       => (isset($item_variety) ? $item_variety : 0)
    ],
    'limit' => 1
]);

if (!empty($item_exists)) {
    quit('You already have an item with these categories!');
}

$listing_added = $Item->add([
    'grower_operation_id'   => $User->GrowerOperation->id,
    'food_category_id'      => $item_category,
    'food_subcategory_id'   => $item_subcategory,
    'item_variety_id'       => (isset($item_variety) ? $item_variety : 0),
    'name'                  => (!empty($item_name) ? $item_name : NULL),
    'quantity'              => $quantity,
    'is_available'          => $is_available,
    'price'                 => $price * 100,
    'weight'                => (isset($weight)) ? $weight : 0,
    'units'                 => (isset($weight, $units)) ? $units : '',
    'wholesale_price'       => $wholesale_price * 100,
    'wholesale_weight'      => (isset($wholesale_weight)) ? $wholesale_weight : 0,
    'wholesale_units'       => (isset($wholesale_weight, $wholesale_units)) ? $wholesale_units : '',
    'packaging'             => $packaging,
    'description'           => $description,
]);

if (!$listing_added) quit('Could not add item');

$id = $listing_added['last_insert_id'];

$Item = new FoodListing([
    'DB' => $DB,
    'id' => $id
]);

// $other_subcategory = strtolower(preg_replace('/\s+/', '', $other_subcategory));

$Image = new Image();

// validate image
if (isset($_POST['images'])) {
    // decode stringified JSON
    $_POST['images'] = html_entity_decode($_POST['images']);
    $images = json_decode($_POST['images'], true);

    // make sure the user didn't tamper with the data
    if (!ctype_digit((string)$images['frame']['w']) || !ctype_digit((string)$images['frame']['h'])) {
        quit('We were unable to crop your images');
    }

    // only one image so key is always 0
    $key = 0;

    // get image
    $image = $images['images'][$key];
    
    // validate image
    $valid = validate_image($image);

    if (!$valid) {
        quit($valid);
    }

    // compile file data
    $file = [
        'name' => $_FILES['img' . $image['key']]['name'],
        'type' => $_FILES['img' . $image['key']]['type'],
        'tmp'  => $_FILES['img' . $image['key']]['tmp_name'],
        'size' => $_FILES['img' . $image['key']]['size']
    ];
    
    // set filename
    $filename = 'fl.' . $id;

    // quit(json_encode(explode('/', $_FILES['listing-image'])));
    // quit(json_encode(explode('/', $file['type'])));
    // determine file type
    $ext = (explode('/', $file['type'])[1] == 'jpeg') ? 'jpg' : 'png';
    // $ext = (explode('/', $_FILES['listing-image']['type'])[1] == 'jpeg') ? 'jpg' : 'png';
    
    // set temporary storage paths
    $tmp1 = SERVER_ROOT . 'media/tmp/start/';
    $tmp2 = SERVER_ROOT . 'media/tmp/final/';
    
    // set temporary image names
    $tmp1_image = $tmp1 . $filename . '.' . $ext;
    $tmp2_image = $tmp2 . $filename . '.' . $ext;
    
    // temporarily store the file
    move_uploaded_file($file['tmp'], $tmp1_image);
    
    // convert to JPG if PNG
    if ($ext == 'png') {
        // Since this is the first time we open the file, ensure that it isn't
        // corrupted at this point with a temporary custom error handler.
        // Don't forget to call restore_error_handler() after checking.
        set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    
        try {
            $Image->load($tmp1_image);
            $jpg_filesize = $Image->convert_png_to_jpg($tmp1 . $filename . '.jpg');
        } catch(ErrorException $e) {
            quit('We were unable to process your image');
        }
        
        restore_error_handler();
    
        // proceed with the smaller file
        if ($jpg_filesize < $file['size']) {
            // delete the PNG
            if (file_exists($tmp1_image)) {
                unlink($tmp1_image);
            }
    
            // set up the JPG as the image to proceed with
            $extension  = 'jpg';
            $tmp1_image = $tmp1 . $filename . '.' . $extension;
            $tmp2_image = $tmp2 . $filename . '.' . $extension;
        } else {
            // delete the JPG
            if (file_exists($tmp1 . $filename . '.jpg')) {
                unlink($tmp1 . $filename . '.jpg');
            }
        }
    }
    
    list($true_width, $true_height, $type, $attr) = getimagesize($tmp1_image);
    
    if ($image['crop']['x'] == 0 
    && $image['crop']['y'] == 0
    && $image['crop']['w'] == $true_width
    && $image['crop']['h'] == $true_height
    ) {
        copy($tmp1_image, $tmp2_image);
    } else {
        $Image->load($tmp1_image);
        $Image->crop($image['crop']['x'], $image['crop']['y'], $image['crop']['w'], $image['crop']['h']);
        $Image->save($tmp2_image);
    }
    
    $final = [
        'w' => 933,
        'h' => 800,
        'file' => $tmp2 . $filename . '.cropped.' . $ext
    ];
    
    if ($true_width == $final['w'] && $true_height == $final['h']) {
        copy($tmp2_image, $final['file']);
    } else {
        $Image->load($tmp2_image);
        $Image->resize($final['w'], $final['h'], 'exact');
        $Image->save($final['file']);
    }
    
    $record_added = $Item->add([
        'food_listing_id' => $id,
        'filename' => $filename,
        'ext' => $ext
    ], 'food_listing_images');
    
    if (!$record_added) quit('Could not add image record');
    
    $img_added = $S3->save_object(ENV . '/items/' . $filename . '.' . $ext, fopen($final['file'], 'r'));
    
    if (!$img_added) quit('Could not add new image');

    // unlink tmp imgs
    if (file_exists($tmp1_image)) {
        unlink($tmp1_image);
    }
    
    if (file_exists($tmp2_image)) {
        unlink($tmp2_image);
    }
    
    if (file_exists($tmp2 . $filename . '.cropped.' . $ext)) {
        unlink($tmp2 . $filename . '.cropped.' . $ext);
    }
}

// reinitialize User & Operation for fresh check
$User = new User([
    'DB' => $DB,
    'id' => $USER['id']
]);

if (isset($_SESSION['user']['active_operation_id']) && $_SESSION['user']['active_operation_id'] != $User->GrowerOperation->id) {
    $User->GrowerOperation = $User->Operations[$_SESSION['user']['active_operation_id']];
}

$is_active = $User->GrowerOperation->check_active();

$json['is_active']  = $is_active;
$json['link']       = $User->GrowerOperation->link . '/' . $Item->link;

echo json_encode($json);

?>  