<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3Incubator\SurfcampEvents\Controller\EventController;
use TYPO3Incubator\SurfcampEvents\Hook\DataHandlerHook;
use TYPO3Incubator\SurfcampEvents\Controller\RegistrationController;
use TYPO3Incubator\SurfcampEvents\Form\Element\SelectWithTimezoneValidation;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'SurfcampEvents',
    'EventList',
    [
        EventController::class => 'list, detail',
        RegistrationController::class => 'processRegistration, processAppointmentRegistration'
    ],
    [
        EventController::class => 'list, detail',
        RegistrationController::class => 'processRegistration, processAppointmentRegistration'
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);


ExtensionUtility::configurePlugin(
    'SurfcampEvents',
    'EventLocationsMap',
    [EventController::class => 'locationsMap'],
    [EventController::class => 'locationsMap',],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SurfcampEvents',
    'EventTimeline',
    [EventController::class => 'timeline'],
    [EventController::class => 'timeline',],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['event'] =
    DataHandlerHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][] = [
    'nodeName' => 'selectSingleWithTimezoneValidation',
    'priority' => 50,
    'class' => SelectWithTimezoneValidation::class,
];
