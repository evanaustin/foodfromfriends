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
        $Buyer,
        $Seller;
        
    function __construct($parameters) {
        $this->table = 'order_exchanges';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);

            if (isset($parameters['buyer_id'])) {
                $this->Buyer = new User([
                    'DB' => $this->DB,
                    'id' => $parameters['buyer_id'],
                    'limited' => true
                ]);
            }

            if (isset($parameters['seller_id'])) {
                $this->Seller = new GrowerOperation([
                    'DB' => $this->DB,
                    'id' => $parameters['seller_id']
                ],[
                    'details' => true,
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
        if (isset($this->Buyer) && isset($this->Seller)) {
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
                $this->address_line_1 = $this->Buyer->address_line_1;
                $this->address_line_2 = $this->Buyer->address_line_2;
                $this->city           = $this->Buyer->city;
                $this->state          = $this->Buyer->state;
                $this->zipcode        = $this->Buyer->zipcode;

                break;
            case 'pickup':
                // use seller address
                $this->address_line_1 = $this->Seller->details['address_line_1'];
                $this->address_line_2 = $this->Seller->details['address_line_2'];
                $this->city           = $this->Seller->details['city'];
                $this->state          = $this->Seller->details['state'];
                $this->zipcode        = $this->Seller->details['zipcode'];

                break;
            case 'meetup':
                // use meetup address
                $this->address_line_1 = $this->Seller->Meetup->address_line_1;
                $this->address_line_2 = $this->Seller->Meetup->address_line_2;
                $this->city           = $this->Seller->Meetup->city;
                $this->state          = $this->Seller->Meetup->state;
                $this->zipcode        = $this->Seller->Meetup->zipcode;
        }

        $this->update([
            'address_line_1'    => $this->address_line_1,
            'address_line_2'    => $this->address_line_2,
            'city'              => $this->city,
            'state'             => $this->state,
            'zipcode'           => $this->zipcode,
        ]);
    }

    /**
     * Given the exchange method selected for this grower in this order, calculate and save the distance.
     */
    private function calculate_distance() {
        // Only delivery stores a distance. For everything else, set as 0
        if ($this->type == 'delivery') {
            $geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $this->Buyer->latitude . ',' . $this->Buyer->longitude . '&destinations=' . $this->Seller->details['lat'] . ',' . $this->Seller->details['lng'] . '&key=' . GOOGLE_MAPS_KEY);
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
                    $fee = $this->Seller->Delivery->fee * ($this->distance - $this->Seller->Delivery->free_distance);
                } else {
                    $fee = $this->Seller->Delivery->fee;
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
        if ($this->type == 'pickup') {
            $this->update([
                'time'          => $this->Seller->Pickup->time,
                'instructions'  => $this->Seller->Pickup->instructions
            ]);
    
            $this->time         = $this->Seller->Pickup->time;
            $this->instructions = $this->Seller->Pickup->instructions;
        } else if ($this->type == 'meetup') {
            $this->update([
                'time'          => $this->Seller->Meetup->time,
                'instructions'  => $this->Seller->Meetup->instructions
            ]);
    
            $this->time         = $this->Seller->Meetup->time;
            $this->instructions = $this->Seller->Meetup->instructions;
        }
    }
}