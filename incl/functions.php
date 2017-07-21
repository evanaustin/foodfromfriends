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

function img($path, $ext, $server = 'local', $class = '') {
    echo '<img src="' . (($server == 'local') ? 'media/' : 'https://s3.amazonaws.com/foodfromfriends/') . $path . '.' . $ext . '"' . (!empty($class) ? 'class="' . $class . '"' : '') .'/>';
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

?>