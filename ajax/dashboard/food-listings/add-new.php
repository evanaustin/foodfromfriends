<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'food-category'     => 'required|integer',
	'food-subcategory'  => 'required|integer',
    'other-subcategory' => 'alpha_space',
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
	'food-category'     => 'trim|whole_number',
	'food-subcategory'  => 'trim|whole_number',
    'other-subcategory' => 'trim|sanitize_string',
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
    'S3' => $S3
]);

if ($other_subcategory) {
    $food_category_title = $FoodListing->other_exists($other_subcategory);

    if ($food_category_title) {
        quit(ucfirst($other_subcategory) . ' already exists as a ' . ucfirst($food_category_title));
    }
}

$listing_added = $FoodListing->add([
    'user_id' => $User->id,
    'food_subcategory_id' => $food_subcategory,
    'other_subcategory' => $other_subcategory,
    'price' => $price * 100,
    'weight' => $weight,
    'units' => $units,
    'quantity' => $quantity,
    'is_available' => $is_available,
    'description' => $description
]);

if (!$listing_added) {
    quit('Could not add listing');
}

$id = $listing_added['last_insert_id'];

$other_subcategory = strtolower(preg_replace('/\s+/', '', $other_subcategory));

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
    $filename = 'fl.' . $id . '.fc.' . (!empty($food_category) ? $food_category : '0') . '.fsc.' . (empty($other_subcategory) ? $food_subcategory : $other_subcategory) . '.u.' . $User->id;

    // determine file type
    $ext = (explode('/', $_FILES['listing-image']['type'])[1] == 'jpeg') ? 'jpg' : 'png';
    
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
        'w' => 630,
        'h' => 540,
        'file' => $tmp2 . $filename . '.cropped.' . $ext
    ];
    
    if ($true_width == $final['w'] && $true_height == $final['h']) {
        copy($tmp2_image, $final['file']);
    } else {
        $Image->load($tmp2_image);
        $Image->resize($final['w'], $final['h'], 'exact');
        $Image->save($final['file']);
    }
    
    $record_added = $FoodListing->add([
        'food_listing_id' => $id,
        'filename' => $filename,
        'ext' => $ext
    ], 'food_listing_images');
    
    if (!$record_added) {
        quit('Could not add image record');
    }
    
    $img_added = $S3->save_object(ENV . '/food-listings/' . $filename . '.' . $ext, fopen($final['file'], 'r'));
    
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

$json['id'] = $id;

echo json_encode($json);

?>  
