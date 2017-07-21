<?php
 
class Routing {
    public
        $path,
        $page,
        $template;

    function __construct($parameters) {
        $this->path = (empty($parameters['path']) ? $parameters['landing'] : $parameters['path']);
        $this->page = str_replace('/', '-', $this->path);

        $exp_path  = explode('/', $this->path);

        $this->template = ($this->path == 'home' || !in_array($exp_path[0], $parameters['backside'])) ? 'front' : $exp_path[0];
    }
}

?>