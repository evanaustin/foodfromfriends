<?php

$settings = [
    'title' => 'Your status | Food From Friends'
];

$listing_count = $User->GrowerOperation->count_listings();

$team_member_count = count($User->GrowerOperation->get_team_members());

switch($User->GrowerOperation->type) {
    case 'none':
        $requirements = [
            'add profile picture' => [
                'link'      => 'dashboard/account/edit-profile/basic-information',
                'status'    => (($User->filename) ? 'complete' : 'incomplete'),
            ],
            'set your location' => [
                'link'      => 'dashboard/account/edit-profile/location',
                'status'    => (!empty($User->zipcode) ? 'complete' : 'incomplete'),
            ]
        ];

        $goals = [];

        break;
    default:
        $requirements = [
            'add operation photo' =>  [
                'link'      => 'dashboard/grower/operation-settings/basic-information',
                'status'    => (!empty($User->GrowerOperation->filename) ? 'complete' : 'incomplete'),
            ],
            'set operation location' =>  [
                'link'      => 'dashboard/grower/operation-settings/location',
                'status'    => (!empty($User->GrowerOperation->zipcode) ? 'complete' : 'incomplete'),
            ]
        ];

        $goals = [
            'add profile picture' =>  [
                'link'      => 'dashboard/account/edit-profile/basic-information',
                'status'    => (!empty($User->filename) ? 'complete' : 'incomplete'),
            ],
            'add your location' =>  [
                'link'      => 'dashboard/account/edit-profile/location',
                'status'    => (!empty($User->zipcode) ? 'complete' : 'incomplete'),
            ],
            'add team members' =>  [
                'link'      => 'dashboard/grower/operation-settings/team-members',
                'status'    => (($team_member_count > 1) ? 'complete' : 'incomplete'),
            ]
        ];

        break;
}

$requirements += [
    'upload your first listing' =>  [
        'link'      => 'dashboard/grower/food-listings/add-new',
        'status'    => (($listing_count > 0) ? 'complete' : 'incomplete'),
    ],
    'enable at least one exchange option' =>  [
        'link'      => 'dashboard/grower/exchange-options/delivery',
        'status'    => (!empty($User->GrowerOperation->Delivery->is_offered) || !empty($User->GrowerOperation->Pickup->is_offered) || !empty($User->GrowerOperation->Meetup->is_offered) ? 'complete' : 'incomplete'),
    ]
];

$goals += [
    'add personal bio' =>  [
        'link'      => 'dashboard/account/edit-profile/basic-information',
        'status'    => (!empty($User->bio) ? 'complete' : 'incomplete'),
    ],
    'sell your first listing' =>  [
        'link'      => 'grower?id=' . $User->GrowerOperation->id,
        'status'    => ((false) ? 'complete' : 'incomplete'),
    ]
];

?>