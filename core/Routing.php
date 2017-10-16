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
        } else if ($this->path == 'log-in') {
            $this->template = 'log-in';
        } else if ($this->path == 'early-access-invitation') {
            $this->template = 'early-access-invitation';
        } else if ($this->path == 'team-member-invitation') {
            $this->template = 'team-member-invitation';
        } else if ($this->path == 'stripe-atlas') {
            $this->template = 'stripe-atlas';
        } else if ($this->path == 'map') {
            $this->template = 'map';
        } else if ($exp_path[0] == 'dashboard') {
            $this->template = 'dashboard';
        } else {
            $this->template = 'front';
        }
        
        if ($this->template == 'dashboard') {
            $this->section = (isset($exp_path[1])) ? $exp_path[1] : null;
            $this->subsection = (isset($exp_path[2])) ? $exp_path[2] : null;
            $this->page = (isset($exp_path[3])) ? $exp_path[3] : null;
        }
    }
}

?>