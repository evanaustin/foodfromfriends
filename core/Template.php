<?php
 
class Template {
    public
        $styles,
        $initializer,
        $architecture,
        $scripts;
    
    function __construct($Routing) {
        $construct = [
            'styles'        => 'css/' . $Routing->template,
            'initializer'   => 'routes/initializers/' . $Routing->path,
            'architecture'  => 'routes/architecture/' . $Routing->template,
            'scripts'       => [
                'js/views/' . ($Routing->template == 'front' ? 'front/' : '') . $Routing->path
            ]
        ];

        foreach ($construct as $k => $v) {
            $this->{$k} = $v;
        }

        if ($Routing->template == 'front' || $Routing->template == 'map') {
            array_unshift($this->scripts, 'js/front');
        }
    }

}

?>