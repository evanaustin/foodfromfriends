<?php

function layer($language, $files) {
    foreach ($files as $file) {
        $file .= '.' . $language;
        
        if (file_exists(SERVER_ROOT . $file)) {
            $file_path = PUBLIC_ROOT . $file;
            
            switch ($language) {
                case 'css':
                    echo '<link rel="stylesheet" href="' . $file_path . '"/>';
                    break;
                case 'js':
                    echo '<script src="' . $file_path . '"></script>';
                    break;
            }
        }
    }
}

function amount($amount, $dollar = true) {
    echo ($dollar ? '$' : ' ') . number_format($amount / 100, 2);
}

/*
 * $data = [
 *  server
 *  class
 *  title
 * ]
 */
function img($path, $ext, $params) {
    echo '<img src="' . (($params['server'] == 'local') ? PUBLIC_ROOT . 'media/' : 'https://s3.amazonaws.com/foodfromfriends/') . $path . '.' . $ext . '"' . (!empty($params['class']) ? 'class="' . $params['class'] . '"' : '') . (!empty($params['title']) ? 'title="' . $params['title'] . '"' : '') .'/>';
}

function _img($path, $ext, $params) {
    echo '<img src="' . (($params['server'] == 'local') ? PUBLIC_ROOT . 'media/' : 'https://s3.amazonaws.com/foodfromfriends/') . $path . '.' . $ext . '"' . (!empty($params['class']) ? 'class="' . $params['class'] . '"' : '') . (!empty($params['title']) ? 'title="' . $params['title'] . '"' : '') .'/>';
}

function svg($path) {
    $src = 'media/' . $path . '.svg';
    echo file_get_contents($src);
}

function quit($message) {
    $json['error'] = $message;
    $json['success'] = false;
    return exit(json_encode($json));
}

function console_log($data) {
    echo "<script>console.log('PHP log: " . (is_array($data) ? json_encode($data) : $data) . "');</script>";
}

function pre($print) {
    echo '<pre>';
    print_r($print);
    echo '</pre>';
}

function validate_image($image) {
    // Make sure the user didn't tamper with the data
    if (!ctype_digit((string)$image['crop']['x']) 
        || !ctype_digit((string)$image['crop']['y']) 
        || !ctype_digit((string)$image['crop']['w']) 
        || !ctype_digit((string)$image['crop']['h']) 
        || !ctype_digit((string)$image['source']['w']) 
        || !ctype_digit((string)$image['source']['h'])
        || !ctype_digit((string)$image['key'])) {
        return (string)$image['source']['w'];
    }

    $mb = 5;

    // Check image attributes
    $allowed = [
        'size'       => $mb * 1024 * 1024,
        'mimetypes'  => ['image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/x-png'],
        'extensions' => ['jpg', 'jpeg', 'png']
    ];

    $file = [
        'name' => $_FILES['img' . $image['key']]['name'],
        'type' => $_FILES['img' . $image['key']]['type'],
        'tmp'  => $_FILES['img' . $image['key']]['tmp_name'],
        'size' => $_FILES['img' . $image['key']]['size']
    ];

    if ($file['size'] > $allowed['size']) {
        return 'Please upload an image smaller than ' . $mb . 'mb';
    }

    if (in_array($file['type'], $allowed['mimetypes']) !== true) {
        return 'Please use a PNG or JPG file';
    }
    
    $extension = explode('.', $file['name']);
    $extension = strtolower(end($extension));
    
    if (in_array($extension, $allowed['extensions']) !== true) {
        return 'Please use a JPG or PNG file';
    }

    return true;
}

function rad($x) {
    return $x * pi() / 180;
};

function getDistance($p1, $p2) {
    // $R      = 6378137; // Earth’s mean radius in meter
    $R      = 3959; // Earth’s mean radius in miles
    $dLat   = rad($p2['lat'] - $p1['lat']);
    $dLong  = rad($p2['lat'] - $p1['lat']);
    $a      = sin($dLat / 2) * sin($dLat / 2) +
            cos(rad($p1['lat'])) * cos(rad($p2['lat'])) *
            sin($dLong / 2) * sin($dLong / 2);
    $c      = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $d      = $R * $c;

    return $d; // returns the distance in miles
};

function stars($rating) {
    if ($rating == 0) {
        $stars = '<bold>New</bold>';
    } else {
        $floor  = floor($rating);
        $ceil   = ceil($rating);
        
        $stars  = '';
        
        for ($i = 0; $i < $floor; $i++) {
            $stars .= '<i class="fa fa-star"></i>';
        } if ($floor < $rating && $rating < $ceil) {
            $stars .= '<i class="fa fa-star-half-o"></i>';
        } for ($i = $ceil; $i < 5; $i++) {
            $stars .= '<i class="fa fa-star-o"></i>';
        }
    }

    return $stars;
}

function truncate($string, $limit) {
    $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
    $parts_count = count($parts);
  
    $length = 0;
    $limited = false;

    for ($last_part = 0; $last_part < $parts_count; ++$last_part) {
        $length += strlen($parts[$last_part]);

        if ($length > $limit) {
            $limited = true;
            break;
        }
    }
  
    return implode(array_slice($parts, 0, $last_part)) . (($limited) ? '&hellip;' : '');
}

?>