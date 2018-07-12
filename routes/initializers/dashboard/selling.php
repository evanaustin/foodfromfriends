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

$item_count      = (isset($User->GrowerOperation)) ? $User->GrowerOperation->count_items() : 0;
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
        'link'      => 'dashboard/selling/settings/profile',
        'status'    => (!empty($User->GrowerOperation->filename) ? 'complete' : 'incomplete'),
    ],
    'set your location' => [
        'link'      => 'dashboard/selling/settings/profile',
        'status'    => (!empty($User->GrowerOperation->zipcode) ? 'complete' : 'incomplete'),
    ],
    'upload your first item' => [
        'link'      => 'dashboard/selling/items/add-new',
        'status'    => (($item_count > 0) ? 'complete' : 'incomplete'),
    ],
    /* 'enable at least one exchange option' => [
        'link'      => 'dashboard/selling/exchange-options/pickup',
        'status'    => (!empty($User->GrowerOperation->Delivery->is_offered) || !empty($User->GrowerOperation->Pickup->is_offered) || !empty($User->GrowerOperation->Meetup->is_offered) ? 'complete' : 'incomplete'),
    ], */
    'set your payout details' => [
        'link'      => 'dashboard/selling/settings/payout-settings',
        'status'    => (!empty($payout_settings) ? 'complete' : 'incomplete'),
    ]
];

$goals = [
    'add team members' =>  [
        'link'      => 'dashboard/selling/settings/team-members',
        'status'    => (($team_member_count > 1) ? 'complete' : 'incomplete'),
    ],
    'sell your first item' =>  [
        'link'      => $User->GrowerOperation->link,
        'status'    => (($amount_paid > 0) ? 'complete' : 'incomplete'),
    ]
];

?>