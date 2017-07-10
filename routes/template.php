<?php if (file_exists($initializer)) include $initializer; ?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $settings['title']; ?></title>
        <?php layer('css', [
            'css/thirdparty/bootstrap/bootstrap-reboot',
            'css/thirdparty/bootstrap/bootstrap-grid',
            'css/thirdparty/bootstrap/bootstrap',
            'css/thirdparty/fontawesome-4.7/font-awesome',
            'css/app'
        ]); ?>
    </head>
    <body class="<?php echo $page; ?>">
        <?php
        
        foreach ($body as $part) if (file_exists($part)) include $part;
        
        layer('js', [
            'node_modules/jquery/dist/jquery',
            'js/app',
            'js/ajax',
            $localScript
        ]);
            
        ?>

        <script>var PUBLIC_ROOT = <?php echo json_encode(PUBLIC_ROOT); ?></script>
    </body>
</html>
