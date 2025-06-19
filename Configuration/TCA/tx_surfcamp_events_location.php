<?php

return [
    'ctrl' => [
        'title' => 'Location',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => '',
        'typeicon_classes' => [
            'default' => 'actions-house'
        ],
        'security' => [
            'ignoreWebMountRestriction' => true,
            'ignoreRootLevelRestriction' => true,
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'interface' => [
        'showRecordFieldList' => 'name, street, street_nr, postal_code, city, country, longitude, latitude',
    ],
    'types' => [
        '1' => ['showitem' => 'name, street, street_nr, postal_code, city, country, longitude, latitude'],
    ],
    'columns' => [
        'name' => [
            'label' => 'Name',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'street' => [
            'label' => 'Street',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'street_nr' => [
            'label' => 'Street Nr',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'postal_code' => [
            'label' => 'Post code',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'city' => [
            'label' => 'City',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'country' => [
            'label' => 'Country',
            'config' => [
                'type' => 'input',
                'eval' => 'required,trim',
            ],
        ],
        'latitude' => [
            'label' => 'Latitude',
            'config' => [
                'type' => 'input',
                'eval' => 'double',
                'size' => 20,
            ],
        ],
        'longitude' => [
            'label' => 'Longitude',
            'config' => [
                'type' => 'input',
                'eval' => 'double',
                'size' => 20,
            ],
        ],
        'timezone' => [
            'label' => 'Timezone',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
    ],
];
