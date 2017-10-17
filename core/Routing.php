<?php
 
class Routing {
    public
        $path,
        $landing,
        $fullpage,
        $unique,
        $template,
        $section,
        $subsection,
        $page;

    function __construct($parameters) {
        $this->path = (empty($parameters['path']) ? $parameters['landing'] : $parameters['path']);
        $this->landing = $parameters['landing'];
        $this->fullpage = str_replace('/', '-', $this->path);

        $exp_path  = explode('/', $this->path);

        $this->unique = [
            'splash',
            'early-access-invitation',
            'team-member-invitation',
            'stripe-atlas',
            'log-in',
            'map'
        ];

        if ($exp_path[0] == 'dashboard') {
            $this->template = 'dashboard';
        } else if (in_array($this->path, $this->unique)) {
            $this->template = $this->path;
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