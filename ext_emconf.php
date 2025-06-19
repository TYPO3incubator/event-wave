<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Surfcamp Events',
    'description' => '',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99'
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'TYPO3Incubator\\SurfcampEvents\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'author' => 'The TYPO3 Community',
    'author_email' => 'info@typo3.org',
    'version' => '0.0.1',
];
