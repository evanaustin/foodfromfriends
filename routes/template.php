<?php

$Template = new Template($Routing);

foreach ([
    $Template->initializer,
    $Template->architecture
] as $path) {
    $file = SERVER_ROOT . $path . '.php';
    if (file_exists($file)) include $file;
}

?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php if (isset($settings['title'])) echo $settings['title']; ?></title>
        <link rel="shortcut icon" href="<?php echo PUBLIC_ROOT; ?>media/logos/favicon-32.png" type="image/x-icon">
        <?php layer('css', [
            'css/thirdparty/bootstrap/bootstrap-reboot',
            'css/thirdparty/bootstrap/bootstrap-grid',
            'css/thirdparty/bootstrap/bootstrap',
            'css/thirdparty/fontawesome-4.7/font-awesome',
            'node_modules/tether/dist/css/tether.min',
            'css/app',
            $Template->styles
        ]); ?>
    </head>

    <body class="<?php echo $Routing->template . ' ' . $Routing->fullpage; ?>">
        <?php
        
        foreach ($body as $part) {
            $file = SERVER_ROOT . $part . '.php';
            if (file_exists($file)) include $file;
        }

        layer('js', [
            'node_modules/jquery/dist/jquery',
            'node_modules/tether/dist/js/tether.min',
            'node_modules/parsleyjs/dist/parsley.min',
            'js/thirdparty/bootstrap.min',
            'js/app',
            'js/ajax'
        ]);
            
        layer('js', $Template->scripts);

        ?>

        <script>var PUBLIC_ROOT = <?php echo json_encode(PUBLIC_ROOT); ?></script>
    </body>
</html>
