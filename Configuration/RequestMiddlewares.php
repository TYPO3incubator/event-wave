<?php

return [
    'frontend' => [
        'typo3/surfcamp-events/timezone-api' => [
            'target' => \TYPO3Incubator\SurfcampEvents\Middleware\TimezoneApiMiddleware::class,
            'after' => [
                'typo3/cms-redirects/redirecthandler'
            ],
            'before' => [
                'typo3/cms-frontend/page-resolver'
            ],
        ],
    ],
];
