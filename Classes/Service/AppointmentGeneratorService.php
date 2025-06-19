<?php

namespace TYPO3Incubator\SurfcampEvents\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Appointment;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\AppointmentRepository;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\EventRepository;

readonly class AppointmentGeneratorService
{
    public function __construct(
        private TimezoneService       $timezoneService,
        private EventRepository       $eventRepository,
        private AppointmentRepository $appointmentRepository,
    )
    {
    }

    /**
     * @param $data
     * @return array
     */
    public function generateAppointments($data)
    {

        if (isset($data->event) && $data->event != null) {
            /** @var Event $event */
            $event =  $this->eventRepository->findByUid((int) $data->event);
            $appointment  = new Appointment();
            $event->getAppointment()->attach($appointment);
            $this->eventRepository->update($event);
        }

        $pm  = GeneralUtility::makeInstance(PersistenceManager::class);
        $pm->persistAll();

        return [];
    }
}
