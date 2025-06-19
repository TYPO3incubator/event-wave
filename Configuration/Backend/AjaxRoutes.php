<?php
use TYPO3Incubator\SurfcampEvents\Controller\Backend\EventGeneratorBackendModuleController;

return [
    'surfcamp_events_appointments_generate' => [
        'path' => '/events-management/appointments/generate',
        'target' => EventGeneratorBackendModuleController::class . '::generateByBackendGenerator',
    ],
    'surfcamp_events_appointments_generate_schema' => [
        'path' => '/events-management/appointments/generate_schema',
        'target' => EventGeneratorBackendModuleController::class . '::getGeneratorSchema',
    ],
];
