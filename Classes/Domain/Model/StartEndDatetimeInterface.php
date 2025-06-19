<?php

namespace TYPO3Incubator\SurfcampEvents\Domain\Model;

interface StartEndDatetimeInterface
{
    public function setStartDateTime(\DateTime $dateTime): void;

    public function getStartDateTime(): ?\DateTime;

    public function getEndDateTime(): ?\DateTime;

    public function setEndDateTime(\DateTime $end_date_time): void;

    public function getStartDateTimeUtc(): ?\DateTime;

    public function setStartDateTimeUtc(\DateTime $start_date_time_utc): void;

    public function getTimezone(): string;

    public function setTimezone(string $timezone): void;

    public function getTzdbVersion(): string;

    public function setTzdbVersion(string $tzdb_version): void;

    public function getEndDateTimeUtc(): ?\DateTime;

    public function setEndDateTimeUtc(\DateTime $end_date_time_utc): void;
}
