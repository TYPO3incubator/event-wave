<?php

use TYPO3Incubator\SurfcampEvents\Controller\Backend\EventGeneratorBackendModuleController;

return [
    'events_eventsgenerator' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/events/management',
        'labels' => 'LLL:EXT:surfcamp_events/Resources/Private/Language/locallang_mod_web_eventsgenerator.xlf',
        'extensionName' => 'SurfcampEvents',
        'iconIdentifier' => 'mod-events-management',
        // 'inheritNavigationComponentFromMainModule' => false,
        'controllerActions' => [
            EventGeneratorBackendModuleController::class => [
                'index', 'generateAppointments', 'registrationsOverview', 'appointmentsOverview',
            ],
        ],
    ],
];
