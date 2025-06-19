<?php

namespace TYPO3Incubator\SurfcampEvents\Domain\Model;

trait StartEndDatetimeTrait
{
    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $start_date_time = null;

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $start_date_time_utc = null;

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $end_date_time = null;

    /**
     * @var \DateTime|null
     */
    protected ?\DateTime $end_date_time_utc = null;

    /**
     * @var string
     */
    protected string $timezone = '';

    /**
     * @var string
     */
    protected string $tzdb_version = '';

    public function setStartDateTime(\DateTime $dateTime): void
    {
        $this->start_date_time = $dateTime;
    }

    public function getStartDateTime(): ?\DateTime
    {
        return $this->start_date_time;
    }

    public function getEndDateTime(): ?\DateTime
    {
        return $this->end_date_time;
    }

    public function setEndDateTime(\DateTime $end_date_time): void
    {
        $this->end_date_time = $end_date_time;
    }

    public function getStartDateTimeUtc(): ?\DateTime
    {
        return $this->start_date_time_utc;
    }

    public function setStartDateTimeUtc(\DateTime $start_date_time_utc): void
    {
        $this->start_date_time_utc = $start_date_time_utc;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getTzdbVersion(): string
    {
        return $this->tzdb_version;
    }

    public function setTzdbVersion(string $tzdb_version): void
    {
        $this->tzdb_version = $tzdb_version;
    }

    public function getEndDateTimeUtc(): ?\DateTime
    {
        return $this->end_date_time_utc;
    }

    public function setEndDateTimeUtc(\DateTime $end_date_time_utc): void
    {
        $this->end_date_time_utc = $end_date_time_utc;
    }
}
