<?php
 
class Review extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $reviewer_id,
        $grower_id,
        $content,
        $date;
      
    function __construct($parameters) {
        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);

        if (isset($parameters['user_id'])) $this->configure_object($parameters['user_id']);
    }


    public function join_reviews($data) {
        if (isset($data)) {
            $bind = [
                'data' => $data
            ];
            $reviews = $this->DB->run("
                SELECT * 
                FROM reviews rv
                JOIN users us
                ON rv.reviewer_id = us.id
                    WHERE rv.grower_id = :data
            ", $bind);

        return (isset($reviews)) ? $reviews : false;
        }

        }   

    }

?>