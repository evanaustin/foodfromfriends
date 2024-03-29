<?php
 
class AccountExtension extends Base {
    
    protected
        $class_dependencies,
        $DB;

    /**
     * This is a helper class that loads the child objects and properties therein of BuyerAccounts (and soon GrowerOperations)
     * 
     * @param array parameters = [
     *  DB
     *  account_type
     *  account_id
     *  table
     *  image = NULL
     * ]
     */
    function __construct($parameters) {
        $this->table = $parameters['table'];

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);

        /*
         * Either account or item ID
         */
        if (isset($parameters['field'])) {
            $image = (isset($parameters['image']) && $parameters['image'] == true) ? true : false;
            
            $sql = 'SELECT *';
            
            if ($image) {
                $sql.= ', i.id AS image_id';
            }
            
            $sql.= " FROM {$this->table} t";
            
            if ($image) {
                $sql.= ' LEFT JOIN images i ON i.id = t.image_id';
            }
            
            $sql.= " WHERE {$parameters['field']}=:{$parameters['field']}";

            $sql.= " LIMIT 1";

            $results = $this->DB->run($sql, [
                $parameters['field'] => $parameters['id']
            ]);
            
            if (!isset($results[0])) return false;

            foreach ($results[0] as $k => $v) $this->{$k} = $v; 
        }
    }
    
}