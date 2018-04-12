<?php

$settings = [
    'title' => 'Seller dashboard | Food From Friends'
];

$Payout = new Payout([
    'DB' => $DB
]);

$amount_paid = $Payout->get_amount_paid($User->GrowerOperation->id);

$OrderGrower = new OrderGrower([
    'DB' => $DB
]);

if ($User->GrowerOperation->new_orders) {
    $new_orders = $OrderGrower->get_new($User->GrowerOperation->id);
}

if ($User->GrowerOperation->pending_orders) {
    $pending_orders = $OrderGrower->get_pending($User->GrowerOperation->id);
}

$listing_count      = (isset($User->GrowerOperation)) ? $User->GrowerOperation->count_listings() : 0;
$team_member_count  = (isset($User->GrowerOperation)) ? count($User->GrowerOperation->get_team_members()) : 0;

if (isset($User->GrowerOperation)) {
    $payout_settings = $User->GrowerOperation->retrieve([
        'where' => [
            'seller_id' => $User->GrowerOperation->id
        ],
        'table' => 'seller_payout_settings',
        'limit' => 1
    ]);
}

$requirements = [
    'add a profile photo' => [
        'link'      => 'dashboard/grower/settings/edit-profile',
        'status'    => (!empty($User->GrowerOperation->filename) ? 'complete' : 'incomplete'),
    ],
    'set your location' => [
        'link'      => 'dashboard/grower/settings/edit-profile',
        'status'    => (!empty($User->GrowerOperation->zipcode) ? 'complete' : 'incomplete'),
    ],
    'upload your first listing' => [
        'link'      => 'dashboard/grower/items/add-new',
        'status'    => (($listing_count > 0) ? 'complete' : 'incomplete'),
    ],
    'enable at least one exchange option' => [
        'link'      => 'dashboard/grower/exchange-options/pickup',
        'status'    => (!empty($User->GrowerOperation->Delivery->is_offered) || !empty($User->GrowerOperation->Pickup->is_offered) || !empty($User->GrowerOperation->Meetup->is_offered) ? 'complete' : 'incomplete'),
    ],
    'set your payout details' => [
        'link'      => 'dashboard/grower/settings/payout-settings',
        'status'    => (!empty($payout_settings) ? 'complete' : 'incomplete'),
    ]
];

$goals = [
    'add team members' =>  [
        'link'      => 'dashboard/grower/settings/team-members',
        'status'    => (($team_member_count > 1) ? 'complete' : 'incomplete'),
    ],
    'sell your first listing' =>  [
        'link'      => $User->GrowerOperation->link,
        'status'    => (($amount_paid > 0) ? 'complete' : 'incomplete'),
    ]
];

?>