<?php

namespace TYPO3Incubator\SurfcampEvents\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Appointment;
use TYPO3Incubator\SurfcampEvents\Domain\Model\Event;
use TYPO3Incubator\SurfcampEvents\Enumeration\RegistrationStatus;
use TYPO3Incubator\SurfcampEvents\Service\RegistrationService;

final class RegistrationController extends ActionController
{
    public function __construct(
        protected RegistrationService $registrationService,
    )
    {}

    public function processRegistrationAction(Event $event, string $email): ResponseInterface
    {
        switch ($this->registrationService->processRegistration($event, $email)) {
            case RegistrationStatus::STATUS_INVALID_EMAIL:
                $this->addFlashMessage(
                    'Please provide a valid email address.',
                    'Invalid email address provided.',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            case RegistrationStatus::STATUS_MAX_CAPACITY_REACHED:
                $this->addFlashMessage(
                    'Number of maximum attendees already reached.',
                    'Registration denied',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            case RegistrationStatus::STATUS_ALREADY_REGISTERED:
                $this->addFlashMessage(
                    'This Email address was already registered.',
                    'Already registered.',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
                case RegistrationStatus::STATUS_EVENT_IN_PAST:
                $this->addFlashMessage(
                    'This Event is in the past and does not accept registrations anymore.',
                    'Event already over',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            default:
                $this->addFlashMessage(
                    'Your registration has been confirmed.',
                    'Successful registration.',
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
        }
    }

    protected function processAppointmentRegistrationAction(Event $event, Appointment $appointment, string $email): ResponseInterface
    {
        switch ($this->registrationService->processAppointmentRegistration($appointment, $email)) {
            case RegistrationStatus::STATUS_INVALID_EMAIL:
                $this->addFlashMessage(
                    'Please provide a valid email address.',
                    'Invalid email address provided.',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            case RegistrationStatus::STATUS_MAX_CAPACITY_REACHED:
                $this->addFlashMessage(
                    'Number of maximum attendees already reached.',
                    'Registration denied',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            case RegistrationStatus::STATUS_ALREADY_REGISTERED:
                $this->addFlashMessage(
                    'This Email address was already registered.',
                    'Already registered.',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            case RegistrationStatus::STATUS_EVENT_IN_PAST:
                $this->addFlashMessage(
                    'This Event is in the past and does not accept registrations anymore.',
                    'Event already over',
                    ContextualFeedbackSeverity::WARNING
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
            default:
                $this->addFlashMessage(
                    'Your registration has been confirmed.',
                    'Successful registration.',
                );
                return $this->redirect(
                    'detail',
                    'Event',
                    'SurfcampEvents',
                    ["event" => $event]
                );
        }
    }
}
