<?php
 
class PayoutLineItem extends Base {
    
    protected
        $class_dependencies,
        $DB;

    public
        $id,
        $payout_id,
        $order_grower_id,
        $total;
        
    function __construct($parameters) {
        $this->table = 'payout_line_items';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
        }
    }

    /**
     * Creates an array of every `payout_line_items` record for a given payout.
     *
     * @param int $payout_id
     * @return array
     */
    public function load_for_payout($payout_id) {
        $results = $this->DB->run('
            SELECT id 
            FROM payout_line_items 
            WHERE payout_id = :payout_id
        ', [
            'payout_id' => $payout_id
        ]);

        $line_items = [];

        if (isset($results[0]['id'])) {
            foreach ($results as $result) {
                $line_items []= new PayoutLineItem([
                    'DB' => $this->DB,
                    'id' => $result['id']
                ]);
            }
        }

        return $line_items;
    }
}