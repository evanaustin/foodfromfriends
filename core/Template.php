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
                'js/views/' . $Routing->template . '/' .$Routing->path
            ]
        ];

        foreach ($construct as $k => $v) {
            $this->{$k} = $v;
        }

        $this->set_scripts($Routing->template);
    }

    function set_scripts($template) {
        if ($template == 'splash') {
            $this->scripts['splash'] = 'js/views/splash';
        } else if ($template == 'front') {
            $this->scripts['sign-up'] = 'js/modals/sign-up';
            $this->scripts['log-in'] = 'js/modals/log-in';
        }
    }
}

?>