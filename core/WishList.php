<?php
 
class WishList extends Base {
    
    public
        $id,
        $user_id,
        $item_category_id,
        $item_subcategory_id,
        $item_variety_id;

    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'wish_lists';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);

        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }
}

?>