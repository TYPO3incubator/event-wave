<?php

return [
    'ctrl' => [
        'title' => 'Registration',
        'label' => 'email',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'name,email',
        'typeicon_classes' => [
            'default' => 'actions-check-circle'
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'email, event, appointment',
    ],
    'types' => [
        '1' => ['showitem' => 'email, event, appointment'],
    ],
    'columns' => [
        'email' => [
            'label' => 'Email',
            'config' => [
                'type' => 'email',
            ],
        ],
        'event' => [
            'label' => 'Event',
            'config' => [
                'type' => 'inline',
                // 'foreign_table' => 'tx_surfcamp_events_event',
                // 'minitems' => 1,
                // 'maxitems' => 1,
            ],
        ],
        'appointment' => [
            'label' => 'Appointment',
            'config' => [
                'type' => 'inline',
            ]
        ]
    ],
];
