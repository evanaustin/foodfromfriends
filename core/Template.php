<?php
 
class Template {
    public
        $styles,
        $initializer,
        $architecture,
        $script;
    
    function __construct($Routing) {
        $construct = [
            'styles'       => 'css/' . $Routing->template,
            'initializer'  => 'routes/initializers/' . $Routing->path,
            'architecture' => 'routes/architecture/' . $Routing->template,
            'script'       => 'js/views/' . $Routing->path
        ];

        foreach ($construct as $k => $v) {
            $this->{$k} = $v;
        }
    }
}

?>