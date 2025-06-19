<?php

namespace TYPO3Incubator\SurfcampEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class Registration extends AbstractDomainObject
{
    protected ?Event $event = null;
    protected ?Appointment $appointment = null;
    protected string $email = '';

    public function getEvent(): Event | null
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getAppointment(): Appointment | null
    {
        return $this->appointment;
    }

    public function setAppointment(Appointment $appointment): void
    {
        $this->appointment = $appointment;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
