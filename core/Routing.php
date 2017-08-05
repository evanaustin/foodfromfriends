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

        $this->template = (in_array($exp_path[0], $parameters['backside'])) ? 'back' : 'front';

        if ($this->template == 'back') {
            $this->section = (isset($exp_path[0])) ? $exp_path[0] : null;
            $this->subsection = (isset($exp_path[1])) ? $exp_path[1] : null;
            $this->page = (isset($exp_path[2])) ? $exp_path[2] : null;
        }
    }
}

?>