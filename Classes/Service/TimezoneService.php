<?php

declare(strict_types=1);

namespace TYPO3Incubator\SurfcampEvents\Service;

class TimezoneService
{
    public function __construct(
        private GeolocationApiService $geolocationApiService,
        private TimezoneApiService $timezoneApiService
    ) {}

    public function getTimezoneVersion(): string
    {
        return timezone_version_get();
    }

    public function getUserTimezone(): ?string
    {
        $userData = $this->geolocationApiService->fetchUserData();
        if ($userData && array_key_exists('timezone', $userData)) {
            return $userData['timezone'];
        }
        return null;
    }

    public function getTimezoneByCoords(float $lat, float $long): ?string
    {
        $timezoneData = $this->timezoneApiService->getByCoordinates($lat, $long);
        if ($timezoneData && array_key_exists('timeZone', $timezoneData)) {
            return $timezoneData['timeZone'];
        }
        return null;
    }

    public function convertToUTC(string $dateTime, string $timezone): string
    {
        $localDatetime = str_replace('Z', '', $dateTime);
        $localDatetime = explode('+', $localDatetime)[0];

        $dt = new \DateTime($localDatetime, new \DateTimeZone($timezone));
        $dt->setTimezone(new \DateTimeZone('UTC'));

        return $dt->format('Y-m-d\TH:i:s\Z');
    }
}
