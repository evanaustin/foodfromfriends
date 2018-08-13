<?php
 
class OrderExchange extends Base {
    
    protected
        $class_dependencies,
        $DB;

    public
        $id,
        $type,
        $address_line_1,
        $address_line_2,
        $city,
        $state,
        $zipcode,
        $distance,
        $fee,
        $time,
        $instructions;

    public
        $BuyerAccount,
        $Seller;
        
    function __construct($parameters) {
        $this->table = 'order_exchanges';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);

            if (isset($parameters['buyer_account_id'])) {
                $this->BuyerAccount = new BuyerAccount([
                    'DB' => $this->DB,
                    'id' => $parameters['buyer_account_id'],
                ],[
                    'orders' => false
                ]);
            }

            if (isset($parameters['seller_id'])) {
                $this->Seller = new GrowerOperation([
                    'DB' => $this->DB,
                    'id' => $parameters['seller_id']
                ],[
                    'exchange' => true
                ]);
            }
        }
    }

    /**
     * Set the type for this OrderExchange and all of the settings that go along with it.
     *
     * @param string $type The selected exchange type for the given grower in the given order
     */
    public function set_type($type) {
        $this->update([
            'type' => $type
        ]);

        $this->type = $type;

        // finish configuring this OrderExchange
        $this->sync();
    }

    /**
     * Called when:
     * (1) the type is set
     * (2) the cart is loaded or modified to make sure we have the seller's latest exchange settings
     * 
     * Important: requires this OrderExchange to be fully configured (with Buyer and Seller)
     */
    public function sync() {
        if (isset($this->BuyerAccount) && isset($this->Seller)) {
            $this->set_address();
            $this->calculate_distance();
            $this->calculate_fee();
            $this->set_details();
        } else {
            return false;
        }
    }

    private function set_address() {
        switch($this->type) {
            case 'delivery':
                // use buyer address
                $this->address_line_1 = $this->BuyerAccount->Address->address_line_1;
                $this->address_line_2 = $this->BuyerAccount->Address->address_line_2;
                $this->city           = $this->BuyerAccount->Address->city;
                $this->state          = $this->BuyerAccount->Address->state;
                $this->zipcode        = $this->BuyerAccount->Address->zipcode;

                break;
            default:
                // use meetup address
                $meetup = $this->retrieve([
                    'where' => [
                        'id' => $this->type,
                    ],
                    'table' => 'meetups'
                ]);

                $this->address_line_1 = $meetup[0]['address_line_1'];
                $this->address_line_2 = $meetup[0]['address_line_2'];
                $this->city           = $meetup[0]['city'];
                $this->state          = $meetup[0]['state'];
                $this->zipcode        = $meetup[0]['zipcode'];
        }

        $this->update([
            'address_line_1'    => $this->address_line_1,
            'address_line_2'    => $this->address_line_2,
            'city'              => $this->city,
            'state'             => $this->state,
            'zipcode'           => $this->zipcode
        ]);
    }

    /**
     * Given the exchange method selected for this grower in this order, calculate and save the distance.
     */
    private function calculate_distance() {
        // Only delivery stores a distance. For everything else, set as 0
        if ($this->type == 'delivery') {
            $geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $this->BuyerAccount->Address->latitude . ',' . $this->BuyerAccount->Address->longitude . '&destinations=' . $this->Seller->latitude . ',' . $this->Seller->longitude . '&key=' . GOOGLE_MAPS_KEY);
            $output = json_decode($geocode);
            $distance = explode(' ', $output->rows[0]->elements[0]->distance->text);
            
            $distance = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
        } else {
            $distance = 0;
        }

        $this->update([
            'distance' => $distance
        ]);

        $this->distance = $distance;
    }

    /**
     * Given the exchange method selected for this grower in this order, calculate and save the exchange fee.
     */
    private function calculate_fee() {
        // Only delivery incurs a fee
        if ($this->type == 'delivery') {
            if ($this->distance > $this->Seller->Delivery->free_distance) {
                if ($this->Seller->Delivery->pricing_rate == 'per-mile') {
                    $fee = number_format($this->Seller->Delivery->fee * ($this->distance - $this->Seller->Delivery->free_distance), 0);
                } else {
                    $fee = number_format($this->Seller->Delivery->fee, 0);
                }
            }          
        }
        
        // For everything else, charge $0 (and check for non-chargeable values)
        $this->fee = ((isset($fee) && $fee >= 1) ? $fee : 0);

        $this->update([
            'fee' => $this->fee
        ]);
    }

    /**
     * Given the exchange method selected for this grower in this order, set the time and instruction details.
     */
    private function set_details() {
        if ($this->type != 'delivery') {
            $meetup = $this->retrieve([
                'where' => [
                    'id' => $this->type,
                ],
                'table' => 'meetups'
            ]);
            
            $this->time = "{$meetup[0]['day']} {$meetup[0]['start_time']} - {$meetup[0]['end_time']}";

            $this->update([
                'time' => $this->time,
            ]);
        }
    }
}