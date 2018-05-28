<?php
 
class WholesaleRelationship extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'wholesale_relationships';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
    
    public function approve_request() {
        $results = $this->update([
            'status' => 2
        ]);

        if (!$results) {
            throw new \Exception('Could not approve request');
        }
    }
    
    public function deny_request() {
        $results = $this->update([
            'status' => 0
        ]);

        if (!$results) {
            throw new \Exception('Could not deny request');
        }
    }

}

?>