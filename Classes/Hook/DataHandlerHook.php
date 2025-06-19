<?php

namespace TYPO3Incubator\SurfcampEvents\Hook;

use TYPO3Incubator\SurfcampEvents\Service\TimezoneService;

class DataHandlerHook
{
    public function __construct(
        private TimezoneService $timezoneService
    ) {}

    public function processDatamap_preProcessFieldArray(array &$fieldArray, $table, $id, $parentObject): void
    {
        if ($table === 'tx_surfcamp_events_event' || $table === 'tx_surfcamp_events_appointment') {
            $timezone = $fieldArray['timezone'];

            if (array_key_exists('start_date_time', $fieldArray) && $fieldArray['start_date_time']) {
                $fieldArray['start_date_time_utc'] = $this->timezoneService->convertToUTC($fieldArray['start_date_time'], $timezone);
            }

            if (array_key_exists('end_date_time', $fieldArray) && $fieldArray['end_date_time']) {
                $fieldArray['end_date_time_utc'] = $this->timezoneService->convertToUTC($fieldArray['end_date_time'], $timezone);
            }

            $fieldArray['tzdb_version'] = $this->timezoneService->getTimezoneVersion();
        }

        if ($table === 'tx_surfcamp_events_location') {
            if (array_key_exists('lat', $fieldArray) && $fieldArray['lat']
                && array_key_exists('long', $fieldArray) && $fieldArray['long']) {
                $fieldArray['timezone'] = $this->timezoneService->getTimezoneByCoords($fieldArray['lat'], $fieldArray['long']);
            }
        }
    }
}
