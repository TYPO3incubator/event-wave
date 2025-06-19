<?php

use TYPO3Incubator\SurfcampEvents\Enumeration\EventType;

$timezoneService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3Incubator\SurfcampEvents\Service\TimezoneService::class);

return [
    'ctrl' => [
        'title' => 'Event',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'title,description',
        'typeicon_classes' => [
            'default' => 'actions-calendar'
        ],
        'security' => [
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'title, description, event_type, start_date_time, end_date_time, timezone, appointment, location, registration',
    ],
    'types' => [
        '1' => ['showitem' => 'title, description, event_type, start_date_time, end_date_time, timezone, appointment, location, registration, is_open_for_registrations, maximum_attendee_capacity'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'title' => [
            'label' => 'Title',
            'config' => [
                'type' => 'input',
                'size' => 50,
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
        'event_type' => [
            'label' => 'Event Type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'Standard',
                        'value' => EventType::EVENT_TYPE_STANDARD
                    ],
                    [
                        'label' => 'Recurring',
                        'value' => EventType::EVENT_TYPE_RECURRING
                    ],
                ],
                'default' => 'standard',
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
        'appointment' => [
            'label' => 'Appointments',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_surfcamp_events_appointment',
                'foreign_field' => 'event',
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
                'type' => 'passthrough',
                'foreign_table' => 'tx_surfcamp_events_registration',
                'foreign_field' => 'registration',
            ],
        ],
        'is_open_for_registrations' => [
            'label' => 'Event accepts registrations',
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
