<?php

namespace TYPO3Incubator\SurfcampEvents\Controller\Backend;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Location;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\EventRepository;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\LocationRepository;
use TYPO3Incubator\SurfcampEvents\Service\AppointmentGeneratorService;

#[AsController]
final class EventGeneratorBackendModuleController extends ActionController
{
    const ITEMS_PER_PAGE = 12;
    const MAXIMUM_LINKS = 20;

    public function __construct(
        protected readonly ModuleTemplateFactory     $moduleTemplateFactory,
        private readonly UriBuilder                  $backendUriBuilder,
        private readonly EventRepository             $eventRepository,
        private readonly LocationRepository          $locationRepository,
        private readonly AppointmentGeneratorService $appointmentGeneratorService,
    )
    {
    }

    /**
     *
     * @return void
     */
    public function commonViewPreperation(): void
    {
        // Load Backend Module JavaScript
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->loadJavaScriptModule('@typo3-incubator/surfcamp-events/eventsmanagement_module.js');
    }

    /**
     * @return ResponseInterface
     * @throws \TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function indexAction(): ResponseInterface
    {
        $this->commonViewPreperation();
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);

        $currentPage = $this->request->hasArgument('currentPageNumber')
            ? (int)$this->request->getArgument('currentPageNumber')
            : 1;

        $search = $this->request->hasArgument('search')
            ? (string)$this->request->getArgument('search')
            : '';

        $allEvents = $this->eventRepository->findAllWithSearchTerm($search);
        $eventsPaginator = new QueryResultPaginator($allEvents, $currentPage, self::ITEMS_PER_PAGE,);
        $eventsPagination = new SlidingWindowPagination($eventsPaginator, self::MAXIMUM_LINKS,);

        $moduleTemplate->assignMultiple([
            'events' => [
                'search' => $search,
                'currentPageNumber' => $currentPage,
                'pagination' => $eventsPagination,
                'paginator' => $eventsPaginator,
                'count' => $allEvents->count(),
                'newPid' => 1,
                'newTable' => 'tx_surfcamp_events_event',
                'newReturn' => $this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'),
            ],
            'locations' => $this->locationRepository->findAll(),
            'newLocationReturn' => $this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'),
            'newEventPid' => '1',
            'newLocationPid' => '1',
        ]);

        return $moduleTemplate->renderResponse('Backend/EventGenerator/Index');
    }

    /**
     * @return ResponseInterface
     * @throws RouteNotFoundException
     */
    public function generateAppointmentsAction(): ResponseInterface
    {
        $this->commonViewPreperation();
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        if ($this->request->hasArgument('event')) {
            $moduleTemplate->assignMultiple([
                'event' => $this->eventRepository->findByUid((int)$this->request->getArguments()['event']),
                'events' => [
                    'newPid' => 1,
                    'newTable' => 'tx_surfcamp_events_event',
                    'newReturn' => $this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'),
                ],
            ]);
        } else {
            return $this->redirectToUri($this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'));
        }
        return $moduleTemplate->renderResponse('Backend/EventGenerator/GenerateAppointments');
    }

    /**
     * @return ResponseInterface
     * @throws RouteNotFoundException
     */
    public function appointmentsOverviewAction(): ResponseInterface
    {
        $this->commonViewPreperation();
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        if ($this->request->hasArgument('event')) {
            $moduleTemplate->assignMultiple([
                'event' => $this->eventRepository->findByUid((int)$this->request->getArguments()['event']),
                'events' => [
                    'newPid' => 1,
                    'newTable' => 'tx_surfcamp_events_event',
                    'newReturn' => $this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'),
                ],
            ]);
        } else {
            return $this->redirectToUri($this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'));
        }
        return $moduleTemplate->renderResponse('Backend/EventGenerator/AppointmentsOverview');
    }

    /**
     * @throws RouteNotFoundException
     */
    public function registrationsOverviewAction(): ResponseInterface
    {
        $this->commonViewPreperation();
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        if ($this->request->hasArgument('event')) {
            $moduleTemplate->assignMultiple([
                'event' => $this->eventRepository->findByUid((int)$this->request->getArguments()['event']),
                'events' => [
                    'newPid' => 1,
                    'newTable' => 'tx_surfcamp_events_event',
                    'newReturn' => $this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'),
                ],
            ]);
        } else {
            return $this->redirectToUri($this->backendUriBuilder->buildUriFromRoute('events_eventsgenerator'));
        }
        return $moduleTemplate->renderResponse('Backend/EventGenerator/RegistrationsOverview');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function generateByBackendGenerator(ServerRequestInterface $request): ResponseInterface
    {
        /** @TODO: Check for valid JSON data */
        $generatorResponse = $this->appointmentGeneratorService->generateAppointments(json_decode($request->getBody()->getContents()));
        return $this->jsonResponse(json_encode($generatorResponse, JSON_PRETTY_PRINT));
    }


    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function getGeneratorSchema(ServerRequestInterface $request): ResponseInterface
    {
        if (json_decode($request->getBody()->getContents())->event !== null) {
            $event = $this->eventRepository->findByUid((int) json_decode($request->getBody())->event);
        } else {
            $event = null;
        }
        $schema = [
            "title" => "Appointment Generation",
            "description" => "Here you can generate single and recurring Appointments for the Event. For Exclusion you can set Appointment Generation Exclusions in the Next Version.",
            "type" => "object",
            "format" => "table",
            "required" => [],
            "properties" => [
                "recurringAppointments" => [
                    "type" => "array",
                    "format" => "normal",
                    "title" => "Recurring Appointments",
                    "description" => "Use this for Events that need recurring. You can set the Start and End Date for the Range and the type of recurring.",
                    "uniqueItems" => true,
                    "items" => [
                        "type" => "object",
                        "title" => "Appointment (Recurring)",
                        "properties" => [
                            "title" => [
                                "title" => "Title of the Appointment",
                                "description" => "If Empty the Title will be the Title of the Event",
                                "type" => "string",
                                "format" => "text",
                                "default" => $event->getTitle() ?? '',
                            ],
                            "description" => [
                                "title" => "Description of the Appointment",
                                "description" => "If Empty the Description will be the Description of the Event",
                                "type" => "string",
                                "format" => "textarea",
                                "expand_height" => true,
                                "default" => $event->getDescription() ?? '',
                            ],
                            "timezone" => [
                                "title" => "Timezone of the Appointment",
                                "description" => "Here you can override the Timezone of the Event.",
                                "type" => "string",
                                "enum" => $this->getAllTimeZoneValues(),
                                "enum_titles" => $this->getAllTimeZoneLabels(),
                            ],
                            "location" => [
                                "title" => "Location of the Appointment",
                                "description" => "Here you can override the Location of the Event.",
                                "type" => "string",
                                "default" => $this->getLocationByEvent($event),
                                "enum" => $this->getAllLocationValues(),
                                "options" => [
                                    "enum" => $this->getAllLocationLabels(),
                                ],
                            ],
                            "date_from" => [
                                "title" => "The Start Date of the Base Appointment",
                                "description" => "Select the Start Date of the Appointment",
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ],
                                "default" => $this->getDefaultDateFrom(),
                            ],
                            "date_to" => [
                                "title" => "The End Date of the Base Appointment",
                                "description" => "Select the End Date of the Appointment",
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ],
                                "default" => $this->getDefaultDateTo(),
                            ],
                            "is_open_for_registrations" => [
                                "title" => "Is the Appointment open  for Registrations?",
                                "description" => "Activate to make Registrations possible. Deactivate to make the Appointment only visible for the Organizer.",
                                "type" => "boolean",
                                "format" => "checkbox"
                            ],
                            "maximum_attendee_capacity" => [
                                "title" => "If Registrations are open, how many people can attend?",
                                "description" => "Set to 0 to make the Appointment open for all.",
                                "type" => "string",
                                "default" => 0
                            ],
                            "recurring_options" => [
                                "type" => "object",
                                "title" => "Options for the Recurring Appointment Generation",
                                "description" => "Define how the Recurring Appointments should be generated.",
                                "properties" => [
//                                    '' => [
//                                        'format' => 'info',
//                                        'title' => 'Important',
//                                        'description' => 'Lorem ipsum dolor',
//                                    ],
                                    "recurring_type" => [
                                        "title" => "Recurring Generation Type",
                                        "description" => "Select the type of recurring you want to generate.",
                                        "type" => "string",
                                        "enum" => ['daily', 'weekly', 'monthly', 'yearly', 'every second week', 'every second month', 'selected weekdays', 'selected days of month'],
                                    ],
                                    "recurring_week_days" => [
                                        "title" => "Selected weekdays",
                                        "description" => "Select the type of recurring you want to generate.",
                                        "type" => "string",
                                        "format" => "checkbox",
                                        'enum' => ['all days', 'monday', 'thuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                                        'default' => ['all days']
                                    ],
                                    "recurring_month_days" => [
                                        "title" => "Selected Days of month",
                                        "description" => "Select the days of the month you want to generate. Can provided as a comma separated list or as range like 1-15.",
                                        "type" => "string",
                                        "default" => '1-31'
                                    ],
                                ],
                            ],
                        ]
                    ],
                    "default" => []
                ],
                "singleEvents" => [
                    "type" => "array",
                    "format" => "normal",
                    "title" => "Single Appointments",
                    "description" => "Use this for Events that dont need to recurring. You can set the Timezone, Location, Start and End Date.",
                    "uniqueItems" => true,
                    "items" => [
                        "type" => "object",
                        "title" => "Appointment (Single Date)",
                        "properties" => [
                            "title" => [
                                "title" => "Title of the Appointment",
                                "description" => "If Empty the Title will be the Title of the Event",
                                "type" => "string",
                                "format" => "text",
                            ],
                            "description" => [
                                "title" => "Description of the Appointment",
                                "description" => "If Empty the Description will be the Description of the Event",
                                "type" => "string",
                                "format" => "textarea",
                                "expand_height" => true,
                            ],
                            "timezone" => [
                                "title" => "Timezone of the Appointment",
                                "description" => "Here you can override the Timezone of the Event.",
                                "type" => "string",
                                "enum" => $this->getAllTimeZoneValues(),
                                "enum_titles" => $this->getAllTimeZoneLabels(),
                            ],
                            "location" => [
                                "title" => "Location of the Appointment",
                                "description" => "Here you can override the Location of the Event.",
                                "type" => "string",
                                "default" => $this->getLocationByEvent($event),
                                "enum" => $this->getAllLocationValues(),
                                "options" => [
                                    "enum" => $this->getAllLocationLabels(),
                                ],
                            ],
                            "date_from" => [
                                "title" => "The Start Date of the Appointment",
                                "description" => "Select the Start Date of the Appointment",
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ],
                                "default" => $this->getDefaultDateFrom(),
                            ],
                            "date_to" => [
                                "title" => "The End Date of the Appointment",
                                "description" => "Select the End Date of the Appointment",
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ],
                                "default" => $this->getDefaultDateTo(),
                            ],
                            "is_open_for_registrations" => [
                                "title" => "Is the Appointment open  for Registrations?",
                                "description" => "Activate to make Registrations possible. Deactivate to make the Appointment only visible for the Organizer.",
                                "type" => "boolean",
                                "format" => "checkbox"
                            ],
                            "maximum_attendee_capacity" => [
                                "title" => "If Registrations are open, how many people can attend?",
                                "description" => "Set to 0 to make the Appointment open for all.",
                                "type" => "string",
                                "default" => 0
                            ],
                        ],
                    ],
                    "default" => []
                ],
                /*
                "appointmentSingleExclusions" => [
                    "type" => "array",
                    "format" => "table",
                    "title" => "Appointment Exclusions (Single Date)",
                    "description" => "TODO: Add an short and self-explaning description here.",
                    "uniqueItems" => true,
                    "items" => [
                        "type" => "object",
                        "title" => "Appointment Exclusion (Single Date)",
                        "properties" => [
                            "date" => [
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ]
                            ]
                        ]
                    ],
                    "default" => []
                ],
                "appointmentRangeExclusions" => [
                    "type" => "array",
                    "format" => "table",
                    "title" => "Appointment Exclusions (Date Range)",
                    "description" => "TODO: Add an short and self-explaning description here.",
                    "uniqueItems" => true,
                    "items" => [
                        "type" => "object",
                        "title" => "Appointment Exclusion (Date Range)",
                        "properties" => [
                            "date_from" => [
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ]
                            ],
                            "date_to" => [
                                "type" => "string",
                                "format" => "datetime-local",
                                "options" => [
                                    "flatpickr" => []
                                ]
                            ]
                        ]
                    ],
                    "default" => []
                ]
                */
            ]
        ];
        /** @TODO: Check for valid JSON data */
        // $generatorResponse = $this->appointmentGeneratorService->generateAppointments(json_decode($request->getBody()->getContents()));
        return $this->jsonResponse(json_encode($schema, JSON_PRETTY_PRINT));
    }

    /**
     * @return array
     */
    public function getAllTimeZoneValues(): array
    {
        $values = array_map(function ($tz) {
            return strtolower($tz);
        }, \DateTimeZone::listIdentifiers());
        return array_values($values);
    }

    /**
     * @return array
     */
    public function getAllTimeZoneLabels(): array
    {
        return array_values(\DateTimeZone::listIdentifiers());
    }

    /**
     * @param Event|null $event
     * @return string
     */
    public function getLocationByEvent(Event|null $event): string {
        if ($event == null) {
            return '';
        } else {
            if ($event->getLocation() !== null) {
                return $event->getLocation()->getName();
            } else {
                return '';
            }
        }
    }


    /**
     * @return array
     */
    public function getAllLocationValues(): array
    {
        $locationValues = [];
        $locations = $this->locationRepository->findAll();
        /** @var Location $location */
        foreach ($locations as $location) {
            $locationValues[] = $location->getName();
        }
        return array_values($locationValues);
    }

    /**
     * @return array
     */
    public function getAllLocationLabels(): array
    {
        $locationLabels = [];
        $locations = $this->locationRepository->findAll();
        /** @var Location $location */
        foreach ($locations as $location) {
            $locationLabels[] = [
                "title" => $location->getName(),
            ];
            //$location->getName();
        }
        return array_values($locationLabels);
    }

    /**
     * @return string
     */
    public function getDefaultDateFrom (): string {
        return (new \DateTime('today 00:00'))->format('Y-m-d\TH:i');
    }

    /**
     * @return string
     */
    public function getDefaultDateTo (): string {
        return (new \DateTime('today 23:59'))->format('Y-m-d\TH:i');
    }
}
