<?php

namespace TYPO3Incubator\SurfcampEvents\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Event extends AbstractDomainObject implements StartEndDatetimeInterface
{
    use StartEndDatetimeTrait;

    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @var string
     */
    protected string $event_type = '';

    /**
     * @var null|ObjectStorage<Appointment>
     */
    protected $appointment = null;

    /**
     * @var null|ObjectStorage<Registration>
     */
    protected $registration = null;

    /**
     * @var Location|null
     */
    protected mixed $location = null;

    protected int $maximumAttendeeCapacity = 0;
    protected bool $is_open_for_registrations = false;

    /**
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->appointment = new ObjectStorage();
        $this->registration = new ObjectStorage();
    }

    public function __construct()
    {
        $this->initStorageObjects();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getEventType(): string
    {
        return $this->event_type;
    }

    public function setEventType(string $event_type): void
    {
        $this->event_type = $event_type;
    }

    public function getLocation(): mixed
    {
        return $this->location;
    }

    public function setLocation(mixed $location): void
    {
        $this->location = $location;
    }

    public function getAppointment(): ?ObjectStorage
    {
        return $this->appointment;
    }

    public function setAppointment(?ObjectStorage $appointment): void
    {
        $this->appointment = $appointment;
    }

    public function getRegistration(): ?ObjectStorage
    {
        return $this->registration;
    }

    public function setRegistration(?ObjectStorage $registration): void
    {
        $this->registration = $registration;
    }

    public function getMaximumAttendeeCapacity(): int
    {
        return $this->maximumAttendeeCapacity;
    }

    public function setMaximumAttendeeCapacity(int $maximumAttendeeCapacity): void
    {
        $this->maximumAttendeeCapacity = $maximumAttendeeCapacity;
    }

    public function getIsOpenForRegistrations(): bool
    {
        return $this->is_open_for_registrations;
    }

    public function setIsOpenForRegistrations(bool $isOpenForRegistrations): void
    {
        $this->is_open_for_registrations = $isOpenForRegistrations;
    }
}
