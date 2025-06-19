<?php

namespace TYPO3Incubator\SurfcampEvents\Service;

use DateTime;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Appointment;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Registration;
use TYPO3Incubator\SurfcampEvents\Domain\Repository\RegistrationRepository;
use TYPO3Incubator\SurfcampEvents\Enumeration\RegistrationStatus;

final class RegistrationService
{
    public function __construct(
        protected RegistrationRepository $registrationRepository,
    )
    {}

    public function processRegistration(Event $event, string $email): int
    {
        // Check for email validity
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return RegistrationStatus::STATUS_INVALID_EMAIL;
        }

        // Check if maximum capacity isn't reached yet
        $registeredAttendeeCount = $this->registrationRepository->findAttendeeCountByEvent($event);
        if ($registeredAttendeeCount >= $event->getMaximumAttendeeCapacity()) {
            return RegistrationStatus::STATUS_MAX_CAPACITY_REACHED;
        }

        // Check if email was already registered for event
        if ($this->registrationRepository->findEmailAlreadyRegisteredByEvent($event, $email)) {
            return RegistrationStatus::STATUS_ALREADY_REGISTERED;
        }

        // Check if Event is in the past
        if ($event->getEndDateTime() < new DateTime()) {
            return RegistrationStatus::STATUS_EVENT_IN_PAST;
        }

        $this->persistNewRegistration($event, null, $email);
        return RegistrationStatus::STATUS_SUCCESSFUL;
    }

    public function processAppointmentRegistration(Appointment $appointment, string $email): int
    {
        // Check for email validity
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return RegistrationStatus::STATUS_INVALID_EMAIL;
        }

        // Check if maximum capacity isn't reached yet
        $registeredAttendeeCount = $this->registrationRepository->findAttendeeCountByAppointment($appointment);
        if ($registeredAttendeeCount >= $appointment->getMaximumAttendeeCapacity()) {
            return RegistrationStatus::STATUS_MAX_CAPACITY_REACHED;
        }

        // Check if email was already registered for event
        if ($this->registrationRepository->findEmailAlreadyRegisteredByAppointment($appointment, $email)) {
            return RegistrationStatus::STATUS_ALREADY_REGISTERED;
        }

        // Check if Event is in the past
        if ($appointment->getEndDateTime() < new DateTime()) {
            return RegistrationStatus::STATUS_EVENT_IN_PAST;
        }

        $this->persistNewRegistration(null, $appointment, $email);
        return RegistrationStatus::STATUS_SUCCESSFUL;
    }

    private function persistNewRegistration(?Event $event, ?Appointment $appointment, string $email): void
    {
        $registration = new Registration();
        if ($event !== null) {
            $registration->setEvent($event);
        }
        if ($appointment !== null) {
            $registration->setAppointment($appointment);
        }
        $registration->setEmail($email);
        $this->registrationRepository->add($registration);
    }
}
