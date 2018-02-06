<?php
 
class Message extends Base {
    
    public
        $id,
        $user_id,
        $grower_operation_id,
        $body,
        $sent_by,
        $sent_on,
        $read_on;

    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'messages';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);

        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    public function get_buying_inbox($user_id) {
        $results = $this->DB->run("
            SELECT 
                max(id) as id, 
                grower_operation_id

            FROM {$this->table}

            WHERE user_id=:user_id

            GROUP BY grower_operation_id

            ORDER BY sent_on DESC
        ", [
            'user_id' => $user_id
        ]);

        return (isset($results)) ? $results : false;
    }
    
    public function get_selling_inbox($grower_operation_id) {
        $results = $this->DB->run("
            SELECT 
                max(id) as id, 
                user_id

            FROM {$this->table}

            WHERE grower_operation_id=:grower_operation_id

            GROUP BY user_id

            ORDER BY sent_on DESC
        ", [
            'grower_operation_id' => $grower_operation_id
        ]);

        return (isset($results)) ? $results : false;
    }
    
    public function unread_aggregate($User) {
        $sql = "SELECT id FROM {$this->table} WHERE (user_id=:user_id AND read_on IS NULL AND sent_by=:buying_sent_by)";

        $bind = [
            'user_id' => $User->id,
            'buying_sent_by' => 'grower'
        ];
        
        if (isset($User->Operations)) {
            foreach ($User->Operations as $Op) {
                $sql .= ' OR (grower_operation_id=:op_id_' . $Op->id . ' AND read_on IS NULL AND sent_by=:sent_by_' . $Op->id . ')';

                $bind['op_id_' . $Op->id]    = $Op->id;
                $bind['sent_by_' . $Op->id]  = 'user';
            }
        }

        $sql .= ' LIMIT 1';

        $results = $this->DB->run($sql, $bind);

        return (!empty($results)) ? json_encode($results) : false;
    }
    
}

?>