<?php
 
class Template {
    public
        $styles,
        $initializer,
        $architecture,
        $scripts;
    
    function __construct($Routing, $LOGGED_IN) {
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

        if ($Routing->template == 'front') {
            if ($LOGGED_IN) {
                array_unshift($this->scripts, 'js/checkout');
            }

            array_unshift($this->scripts, 'js/front');
        } else if ($Routing->template == 'dashboard') {
            if ($Routing->section == 'messages') {
                array_unshift($this->scripts, 'js/views/dashboard/messages');
            }

            array_unshift($this->scripts, 'js/dashboard');
        }
    }

}

?>