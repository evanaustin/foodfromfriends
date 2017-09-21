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
        $this->landing = $parameters['landing'];
        $this->fullpage = str_replace('/', '-', $this->path);

        $exp_path  = explode('/', $this->path);

        if ($this->path == 'splash') {
            $this->template = 'splash';
        } else if ($this->path == 'early-access-invitation') {
            $this->template = 'early-access-invitation';
        } else if ($this->path == 'map') {
            $this->template = 'map';
        } else if (in_array($exp_path[0], $parameters['backside'])) {
            $this->template = 'back';
        } else {
            $this->template = 'front';
        }
        
        if ($this->template == 'back') {
            $this->section = (isset($exp_path[0])) ? $exp_path[0] : null;
            $this->subsection = (isset($exp_path[1])) ? $exp_path[1] : null;
            $this->page = (isset($exp_path[2])) ? $exp_path[2] : null;
        }
    }
}

?>