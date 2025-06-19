<?php

$timezoneService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3Incubator\SurfcampEvents\Service\TimezoneService::class);

return [
    'ctrl' => [
        'title' => 'Appointment',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'typeicon_classes' => [
            'default' => 'actions-clock'
        ]
    ],
    'interface' => [
        'showRecordFieldList' => 'event, title, description, start_date_time, end_date_time, timezone, location, registration',
    ],
    'types' => [
        '1' => ['showitem' => 'event, title, description, start_date_time, end_date_time, timezone, location, registration, is_open_for_registrations, maximum_attendee_capacity'],
    ],
    'columns' => [
        'title' => [
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'description' => [
            'label' => 'Description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 40,
            ],
        ],
        'start_date_time' => [
            'label' => 'Start Date & Time',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
            ],
        ],
        'start_date_time_utc' => [
            'label' => 'Start Date & Time [UTC]',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
            ],
        ],
        'end_date_time' => [
            'label' => 'End Date & Time',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
                'eval' => 'required',
            ],
        ],
        'end_date_time_utc' => [
            'label' => 'End Date & Time [UTC]',
            'config' => [
                'type' => 'datetime',
                'dbType' => 'datetime',
            ],
        ],
        'timezone' => [
            'label' => 'Timezone',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => array_map(function($tz) {
                    return [$tz, $tz];
                }, \DateTimeZone::listIdentifiers()),
                'default' => $timezoneService->getUserTimezone(),
            ],
        ],
        'tzdb_version' => [
            'label' => 'tzdb version',
            'config' => [
                'type' => 'input',
            ],
        ],
        'location' => [
            'label' => 'Location',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingleWithTimezoneValidation',
                'items' => array(
                    array("-- Please select a location --", 0),
                ),
                'foreign_table' => 'tx_surfcamp_events_location',
            ],
        ],
        'registration' => [
            'label' => 'Registrations',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_surfcamp_events_registration',
                'foreign_field' => 'registration',
            ],
        ],
        'is_open_for_registrations' => [
            'label' => 'Appointment accepts registrations',
            'onChange' => 'reload',
            'config' => [
                'type' => 'check',
            ]
        ],
        'maximum_attendee_capacity' => [
            'label' => 'Maximum Attendee capacity',
            'displayCond' => 'FIELD:is_open_for_registrations:>:0',
            'config' => [
                'type' => 'number',
                'min' => 0
            ]
        ]
    ],
];
