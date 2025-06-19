<?php

declare(strict_types=1);

return [
    \TYPO3Incubator\SurfcampEvents\Domain\Model\Event::class => [
        'tableName' => 'tx_surfcamp_events_event',
        'properties' => [
            'event_type' => [
                'fieldName' => 'event_type',
            ],
            'start_date_time' => [
                'fieldName' => 'start_date_time',
            ],
            'start_date_time_utc' => [
                'fieldName' => 'start_date_time_utc',
            ],
            'end_date_time' => [
                'fieldName' => 'end_date_time',
            ],
            'end_date_time_utc' => [
                'fieldName' => 'end_date_time_utc',
            ],
            'tzdb_version' => [
                'fieldName' => 'tzdb_version',
            ],
            'is_open_for_registrations' => [
                'fieldName' => 'is_open_for_registrations',
            ]
        ],
    ],
    \TYPO3Incubator\SurfcampEvents\Domain\Model\Appointment::class => [
        'tableName' => 'tx_surfcamp_events_appointment',
        'properties' => [
            'is_open_for_registrations' => [
                'fieldName' => 'is_open_for_registrations',
            ],
            'start_date_time' => [
                'fieldName' => 'start_date_time',
            ],
            'start_date_time_utc' => [
                'fieldName' => 'start_date_time_utc',
            ],
            'end_date_time' => [
                'fieldName' => 'end_date_time',
            ],
            'end_date_time_utc' => [
                'fieldName' => 'end_date_time_utc',
            ],
            'tzdb_version' => [
                'fieldName' => 'tzdb_version',
            ],
        ],
    ],
    \TYPO3Incubator\SurfcampEvents\Domain\Model\Location::class => [
        'tableName' => 'tx_surfcamp_events_location',
        'properties' => [],
    ],
    \TYPO3Incubator\SurfcampEvents\Domain\Model\Registration::class => [
        'tableName' => 'tx_surfcamp_events_registration',
        'properties' => [],
    ],
];
