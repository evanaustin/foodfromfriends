<?php
 
class Routing {
    public
        $path,
        $fullpage,
        $template,
        $section,
        $subsection,
        $page;

    function __construct($parameters) {
        $this->path = (empty($parameters['path']) ? $parameters['landing'] : $parameters['path']);
        $this->fullpage = str_replace('/', '-', $this->path);

        $exp_path  = explode('/', $this->path);

        $this->template = ($this->path == 'home' || !in_array($exp_path[0], $parameters['backside'])) ? 'front' : $exp_path[0];

        if ($this->template != 'front') {
            $this->section = (isset($exp_path[1])) ? $exp_path[1] : null;
            $this->subsection = (isset($exp_path[2])) ? $exp_path[2] : null;
            $this->page = (isset($exp_path[3])) ? $exp_path[3] : null;
        }
    }
}

?>